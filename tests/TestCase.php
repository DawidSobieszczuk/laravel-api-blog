<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\Fluent\AssertableJson;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function create_admin()
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    protected function assertPagination($url, $expectedPrePageCount = 15)
    {
        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll(['data', 'links', 'meta'])->where('meta.per_page', $expectedPrePageCount)
            );
    }
}
