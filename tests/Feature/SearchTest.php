<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private const VERSION = 'v1';
    private const ARTICLE_RESPONSE = ['id', 'title', 'content', 'user', 'is_draft', 'categories', 'tags', 'created_at', 'updated_at'];

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
        Article::factory()->create(['categories' => ['aaa']]);
        $url = 'api/' . self::VERSION . '/category';

        $this->assertSearch($url, 'a', 0);
        $this->assertSearch($url, 'aaa', 1);

        $this->assertPagination($url . '/aaa?per_page=5', 5);
        $this->assertPagination($url . '/aaa?per_page=-1', 15);
    }

    public function test_tag()
    {
        Article::factory()->create(['tags' => ['aaa']]);
        $url = 'api/' . self::VERSION . '/tag';

        $this->assertSearch($url, 'a', 0);
        $this->assertSearch($url, 'aaa', 1);

        $this->assertPagination($url . '/aaa?per_page=5', 5);
        $this->assertPagination($url . '/aaa?per_page=-1', 15);
    }

    public function test_search()
    {
        Article::factory()->create([
            'title' => 'title abc',
            'content' => 'content cba',
            'categories' => ['aaa'],
            'tags' => ['bbb'],
        ]);
        Article::factory()->create([
            'title' => 'title xyz',
            'content' => 'content zyx',
            'categories' => ['xxx'],
            'tags' => ['yyy'],
        ]);

        $url = 'api/' . self::VERSION . '/search';

        $this->assertSearch($url, 'a', 1);
        $this->assertSearch($url, 'title', 2);
        $this->assertSearch($url, 'con', 2);
        $this->assertSearch($url, 'cba', 1);
        $this->assertSearch($url, 'aaa', 1);
        $this->assertSearch($url, 'yyy', 1);
        $this->assertSearch($url, 'zero', 0);

        $this->assertPagination($url . '/aaa?per_page=5', 5);
        $this->assertPagination($url . '/aaa?per_page=-1', 15);
    }
}
