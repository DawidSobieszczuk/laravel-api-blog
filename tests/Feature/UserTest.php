<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private const VERSION = 'v1';

    public function test_show()
    {
        $this->seed(UserSeeder::class);
        $url = 'api/' . self::VERSION . '/user';

        $this->getJson($url)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        Sanctum::actingAs(User::first());

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll(['name', 'email', 'is_admin'])
                    ->missing('password')->etc()
            );
    }

    public function test_update()
    {
        $this->seed(UserSeeder::class);
        $url = 'api/' . self::VERSION . '/user';
        $user = User::where('is_admin', 0)->first();
        $oldPasswrodHash = $user->password;

        $this->putJson($url)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));



        Sanctum::actingAs($user);

        $this->putJson($url, [
            'password' => 'newPassword',
        ])
            ->assertStatus(422)
            ->assertInvalid('password');

        $this->putJson($url, [
            'name' => 'newName',
            'password' => 'newPassword',
            'password_confirmation' => 'newPassword',
            'is_admin' => 1,
        ])
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll(['name', 'email', 'is_admin'])
                    ->missing('password')->etc()
            );

        $this->assertTrue(
            $user->name == 'newName'
                && $user->password != $oldPasswrodHash
                && $user->is_admin == 0 // User cannot change is_admin field
        );
    }
}
