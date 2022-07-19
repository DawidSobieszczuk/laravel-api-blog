<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ArticleController extends ApiController
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

    public function index()
    {
        return ArticleResource::collection($this->articleService->getAllArticles($this->allowDraft()));
    }

    public function paginate(Request $request)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $per_page = (int)($fields['per_page'] ?? null);
        $per_page = max($per_page, 0);

        return ArticleResource::collection($this->articleService->getAllArticlesPaginate($this->allowDraft(), $per_page));
    }

    public function store(Request $request)
    {
        return new ArticleResource($this->articleService->createNewArticleFromRequest($request, $this->userService->getCurrentLoggedUser()));
    }

    public function show($id)
    {
        $article = $this->articleService->getArticleById($id, $this->allowDraft());
        return $article ? new ArticleResource($article) : $this->responseNotFound();
    }

    public function update($id, Request $request)
    {
        $article = $this->articleService->updateArticleFromRequest($id, $request);

        if (!$article->is_draft && !$this->userService->getCurrentLoggedUser()->hasRole('editor')) {
            return $this->responseUnauthenticated();
        }

        return $article ? new ArticleResource($article) : $this->responseNotFound();
    }

    public function publish($id)
    {
        $article = $this->articleService->publishArticle($id);
        return $article ? new ArticleResource($article) : $this->responseNotFound();
    }

    public function unpublish($id)
    {
        $article = $this->articleService->unpublishArticle($id);
        return $article ? new ArticleResource($article) : $this->responseNotFound();
    }

    public function destroy($id)
    {
        return $this->articleService->destroyArticleById($id) ? $this->responseMessage('Destroyed') : $this->responseNotFound();
    }
}
