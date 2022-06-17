<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    private function is_user_admin()
    {
        return auth('sanctum')->user() ? auth('sanctum')->user()->is_admin : false;
    }

    public function category(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->getArticlesByCategoryPaginate($slug, $this->is_user_admin(), $per_page));
    }

    public function tag(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->getArticlesByTagPaginate($slug, $this->is_user_admin(), $per_page));
    }

    public function search(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->searchArticlesPaginate($slug, $this->is_user_admin(), $per_page));
    }
}
