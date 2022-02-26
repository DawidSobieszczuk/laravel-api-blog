<?php

namespace Tests\Feature\V1;

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

    public function test_login()
    {
        $this->seed(UserSeeder::class);
        $userEmail = 'user@email.com';
        $userPassword = 'password';

        $this->json('POST', 'api/' . self::VERSION . '/login', [
            'email' => $userEmail,
            'password' => $userPassword,
        ])
            ->assertValid()
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('user', 'token')
            );
        $this->assertDatabaseCount('personal_access_tokens', 1);


        $this->json('POST', 'api/' . self::VERSION . '/login', [
            'email' => $userEmail . '0',
            'password' => $userPassword,
        ])
            ->assertValid()
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->json('POST', 'api/' . self::VERSION . '/login', [
            'email' => $userEmail,
            'password' => $userPassword . '0',
        ])
            ->assertValid()
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->json('POST', 'api/' . self::VERSION . '/login', [
            //'email' => $userEmail,
            'password' => $userPassword,
        ])->assertInvalid(['email']);

        $this->json('POST', 'api/' . self::VERSION . '/login', [
            'email' => 'email',
            'password' => $userPassword,
        ])->assertInvalid(['email']);

        $this->json('POST', 'api/' . self::VERSION . '/login', [
            'email' => $userEmail,
            //'password' => $userPassword,
        ])->assertInvalid(['password']);
    }

    public function test_logout()
    {
        $this->seed(UserSeeder::class);
        $userEmail = 'user@email.com';
        $userPassword = 'password';

        $token = $this->postJson('api/' . self::VERSION . '/login', [
            'email' => $userEmail,
            'password' => $userPassword,
        ])->json()['token'];
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->getJson('api/' . self::VERSION . '/logout')
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->withHeader('Authorization', 'Bearer ' . $token)->getJson('api/' . self::VERSION . '/logout')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
