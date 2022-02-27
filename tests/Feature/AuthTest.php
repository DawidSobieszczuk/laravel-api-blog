<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private const VERSION = 'v1';
    private const USER_EMAIL = 'user@email.com';
    private const USER_PASSWORD = 'password';

    public function test_login()
    {
        $this->seed(UserSeeder::class);

        $url = 'api/' . self::VERSION . '/login';

        $this->postJson($url, [
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD,
        ])
            ->assertValid()
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('user', 'token')
            );
        $this->assertDatabaseCount('personal_access_tokens', 1);


        $this->postJson($url, [
            'email' => self::USER_EMAIL . '0',
            'password' => self::USER_PASSWORD,
        ])
            ->assertValid()
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->postJson($url, [
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD . '0',
        ])
            ->assertValid()
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->postJson($url, [
            //'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD,
        ])->assertInvalid(['email']);

        $this->postJson($url, [
            'email' => 'email',
            'password' => self::USER_PASSWORD,
        ])->assertInvalid(['email']);

        $this->postJson($url, [
            'email' => self::USER_EMAIL,
            //'password' => self::USER_PASSWORD,
        ])->assertInvalid(['password']);
    }

    public function test_logout()
    {
        $this->seed(UserSeeder::class);

        $urlLogin = 'api/' . self::VERSION . '/login';
        $urlLogout = 'api/' . self::VERSION . '/logout';

        $token = $this->postJson($urlLogin, [
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD,
        ])->json()['token'];
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->getJson($urlLogout)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->withHeader('Authorization', 'Bearer ' . $token)->getJson($urlLogout)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
