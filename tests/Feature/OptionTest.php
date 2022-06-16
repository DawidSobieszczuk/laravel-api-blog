<?php

namespace Tests\Feature;

use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OptionTest extends TestCase
{
    use RefreshDatabase;

    private const VERSION = 'v1';

    public function test_index()
    {
        Option::factory()->count(10)->create();
        $url = 'api/' . self::VERSION . '/options';
        // $url = 'api/' . self::VERSION . '/admin/options';

        // $this->getJson($url)
        //     ->assertStatus(401)
        //     ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        // Sanctum::actingAs($this->create_admin());

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')->count('data', 10)->has('data.0', fn ($json) => $json->hasAll(['id', 'name', 'value']))
            );
    }

    public function test_store()
    {
        $url = 'api/' . self::VERSION . '/admin/options';
        $data = [
            'name' => 'Name',
            'value' => 'Value',
        ];

        $this->postJson($url, $data)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        Sanctum::actingAs($this->create_admin());

        $this->postJson($url, $data)
            ->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data.id')
                    ->where('data.name', 'Name')
                    ->where('data.value', 'Value')

            );
    }

    public function test_show()
    {
        Option::factory()->create();
        $url = 'api/' . self::VERSION . '/options/1';
        // $url = 'api/' . self::VERSION . '/admin/options/1';

        // $this->getJson($url)
        //     ->assertStatus(401)
        //     ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        // Sanctum::actingAs($this->create_admin());

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->hasAll('data.id', 'data.name', 'data.value'));
    }

    public function test_update()
    {
        Option::factory()->create();
        $url = 'api/' . self::VERSION . '/admin/options/1';
        $data = [
            'value' => 'newValue',
        ];

        $this->putJson($url, $data)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        Sanctum::actingAs($this->create_admin());

        $this->putJson($url, $data)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll('data.id', 'data.name')
                    ->where('data.value', 'newValue')
            );
    }

    public function test_destroy()
    {
        $option = Option::factory()->create();
        $url = 'api/' . self::VERSION . '/admin/options/1';

        $this->deleteJson($url)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));
        $this->assertModelExists($option);

        Sanctum::actingAs($this->create_admin());

        $this->deleteJson($url)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));
        $this->assertModelMissing($option);
    }
}
