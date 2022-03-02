<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;
    private const VERSION = 'v1';
    private const RESPONSE_KEYS = ['id', 'title', 'content', 'user', 'is_draft', 'categories', 'tags', 'created_at', 'updated_at'];

    private function create_admin()
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    public function test_index()
    {
        Article::factory()->count(10)->state(
            new Sequence(
                ['is_draft' => false],
                ['is_draft' => true],
            )
        )->create();
        $url = 'api/' . self::VERSION . '/articles';

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(5)
            );

        Sanctum::actingAs($this->create_admin());

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(10)
            );
    }

    public function test_store()
    {
        $url = 'api/' . self::VERSION . '/admin/articles';
        $data = [
            'title' => 'title',
            'content' => 'conntent',
            'is_draft' => true,
            'categories' => ['One', 'Two'],
            'tags' => ['one', 'two'],
        ];

        $this->postJson($url, $data)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        Sanctum::actingAs($this->create_admin());

        $this->postJson($url, $data)
            ->assertValid()
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => $json->hasAll(self::RESPONSE_KEYS));
    }

    public function test_show()
    {
        Article::factory()->count(2)->state(
            new Sequence(
                ['is_draft' => false],
                ['is_draft' => true],
            )
        )->create();

        $url = 'api/' . self::VERSION . '/articles';

        $this->getJson($url . '/1')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->hasAll(self::RESPONSE_KEYS));

        $this->getJson($url . '/2')
            ->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        Sanctum::actingAs($this->create_admin());

        $this->getJson($url . '/1')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->hasAll(self::RESPONSE_KEYS));

        $this->getJson($url . '/2')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->hasAll(self::RESPONSE_KEYS));
    }

    public function test_update()
    {
        Article::factory()->create();
        $url = 'api/' . self::VERSION . '/admin/articles/1';
        $data = [
            'title' => 'newTitle',
        ];

        $this->putJson($url, $data)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));

        Sanctum::actingAs($this->create_admin());

        $this->putJson($url, $data)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->hasAll(self::RESPONSE_KEYS)->where('title', 'newTitle'));
    }

    public function test_destroy()
    {
        $article = Article::factory()->create();
        $url = 'api/' . self::VERSION . '/admin/articles/1';

        $this->deleteJson($url)
            ->assertStatus(401)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));
        $this->assertModelExists($article);

        Sanctum::actingAs($this->create_admin());

        $this->deleteJson($url)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('message'));
        $this->assertSoftDeleted($article);
    }
}
