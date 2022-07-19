<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    protected ArticleService $articleService;
    protected UserService $userService;

    public function __construct(ArticleService $articleService, UserService $userService)
    {
        $this->articleService = $articleService;
        $this->userService = $userService;
    }

    private function allowDraft()
    {
        return auth('sanctum')->user() ? $this->userService->getCurrentLoggedUser()->hasRole('writer') : false;
    }

    public function category($slug)
    {
        return ArticleResource::collection($this->articleService->getArticlesByCategory($slug, $this->allowDraft()));
    }

    public function categoryPaginate(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->getArticlesByCategoryPaginate($slug, $this->allowDraft(), $per_page));
    }

    public function tag($slug)
    {
        return ArticleResource::collection($this->articleService->getArticlesByTag($slug, $this->allowDraft()));
    }

    public function tagPaginate(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->getArticlesByTagPaginate($slug, $this->allowDraft(), $per_page));
    }

    public function search($slug)
    {
        return ArticleResource::collection($this->articleService->searchArticles($slug, $this->allowDraft()));
    }

    public function searchPaginate(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->searchArticlesPaginate($slug, $this->allowDraft(), $per_page));
    }
}
