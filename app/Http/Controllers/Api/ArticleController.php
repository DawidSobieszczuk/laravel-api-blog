<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends ApiController
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    private function isUserAdmin()
    {
        return auth('sanctum')->user() ? auth('sanctum')->user()->is_admin : false;
    }

    public function index(Request $request)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->getAllArticlesPaginate($this->isUserAdmin(), $per_page));
    }

    public function store(Request $request)
    {
        return new ArticleResource($this->articleService->createNewArticleFromRequest($request));
    }

    public function show($id)
    {
        $article = $this->articleService->getArticleById($id, $this->isUserAdmin());
        return $article ? new ArticleResource($article) : $this->responseNotFound();
    }

    public function update($id, Request $request)
    {
        $article = $this->articleService->updateArticleFromRequest($id, $request);
        return $article ? new ArticleResource($article) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->articleService->destroyArticleById($id) ? $this->responseMessage('Destroyed') : $this->responseNotFound();
    }
}
