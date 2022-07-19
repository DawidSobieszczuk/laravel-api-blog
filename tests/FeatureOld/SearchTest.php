<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private const VERSION = 'v1';
    private const ARTICLE_RESPONSE = ['id', 'title', 'content', 'user', 'is_draft', 'categories', 'tags', 'created_at', 'updated_at', 'thumbnail', 'excerpt'];

    private function assertSearch($url, $slug, $dataCount)
    {
        $this->getJson($url . '/' . $slug)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => ($dataCount == 0) ? $json->hasAll(['data', 'links', 'meta'])->count('data', $dataCount)
                    : $json->hasAll(['data', 'links', 'meta'])->count('data', $dataCount)->has('data.0', fn ($json) => $json->hasAll(self::ARTICLE_RESPONSE))
            );
    }

    public function test_category()
    {
        Article::factory()->count(10)->state(
            new Sequence(
                ['is_draft' => false],
                ['is_draft' => true],
            )
        )->create(['categories' => ['aaa']]);
        $url = 'api/' . self::VERSION . '/category';

        $this->assertSearch($url, 'a', 0);
        $this->assertSearch($url, 'aaa', 5);
        Sanctum::actingAs($this->create_admin());
        $this->assertSearch($url, 'aaa', 10);


        $this->assertPagination($url . '/aaa?per_page=5', 5);
        $this->assertPagination($url . '/aaa?per_page=-1', 15);
    }

    public function test_tag()
    {
        Article::factory()->count(10)->state(
            new Sequence(
                ['is_draft' => false],
                ['is_draft' => true],
            )
        )->create(['tags' => ['aaa']]);
        $url = 'api/' . self::VERSION . '/tag';

        $this->assertSearch($url, 'a', 0);
        $this->assertSearch($url, 'aaa', 5);
        Sanctum::actingAs($this->create_admin());
        $this->assertSearch($url, 'aaa', 10);

        $this->assertPagination($url . '/aaa?per_page=5', 5);
        $this->assertPagination($url . '/aaa?per_page=-1', 15);
    }

    public function test_search()
    {
        Article::factory()->count(2)->state(
            new Sequence(
                ['is_draft' => false],
                ['is_draft' => true],
            )
        )->create([
            'title' => 'title abc',
            'content' => 'content cba',
            'categories' => ['aaa'],
            'tags' => ['bbb'],
        ]);
        Article::factory()->count(4)->state(
            new Sequence(
                ['is_draft' => false],
                ['is_draft' => true],
            )
        )->create([
            'title' => 'title xyz',
            'content' => 'content zyx',
            'categories' => ['xxx'],
            'tags' => ['yyy'],
        ]);

        $url = 'api/' . self::VERSION . '/search';

        $this->assertSearch($url, 'a', 1);
        $this->assertSearch($url, 'title', 3);
        $this->assertSearch($url, 'con', 3);
        $this->assertSearch($url, 'cba', 1);
        $this->assertSearch($url, 'aaa', 1);
        $this->assertSearch($url, 'yyy', 2);
        $this->assertSearch($url, 'zero', 0);

        Sanctum::actingAs($this->create_admin());
        $this->assertSearch($url, 'a', 2);
        $this->assertSearch($url, 'title', 6);
        $this->assertSearch($url, 'con', 6);
        $this->assertSearch($url, 'cba', 2);
        $this->assertSearch($url, 'aaa', 2);
        $this->assertSearch($url, 'yyy', 4);

        $this->assertPagination($url . '/aaa?per_page=5', 5);
        $this->assertPagination($url . '/aaa?per_page=-1', 15);
    }
}
