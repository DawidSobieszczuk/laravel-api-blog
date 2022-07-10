<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;

class ArticleService
{
    protected ArticleRepository $articleRepository;
    protected UserService $userService;

    public function __construct(ArticleRepository $articleRepository, UserService $userService)
    {
        $this->articleRepository = $articleRepository;
        $this->userService = $userService;
    }

    public function getAllArticles(bool $isDraft = false)
    {
        return $this->articleRepository->all($isDraft);
    }

    public function getAllArticlesPaginate(bool $isDraft = false, $perPage = null)
    {
        return $this->articleRepository->paginate($isDraft, $perPage);
    }

    public function getArticlesByCategoryPaginate($categoryName, bool $isDraft = false, $perPage = null)
    {
        return $this->articleRepository->searchByCategory($categoryName, $isDraft, $perPage);
    }

    public function getArticlesByTagPaginate($tagName, bool $isDraft = false, $perPage = null)
    {
        return $this->articleRepository->searchByTag($tagName, $isDraft, $perPage);
    }

    public function searchArticlesPaginate($slug, bool $isDraft = false, $perPage = null)
    {
        return $this->articleRepository->search($slug, $isDraft, $perPage);
    }

    public function createNewArticleFromRequest(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'thumbnail' => 'required|string',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'is_draft' => 'boolean',
            'categories' => 'array',
            'tags' => 'array',
        ]);

        $fields['is_draft'] = $fields['is_draft'] ?? true;
        $fields['categories'] = $fields['categories'] ?? [];
        $fields['tags'] = $fields['tags'] ?? [];

        $article = $this->articleRepository->create($fields, $this->userService->getCurrentLoggedUser());
        $article->load('user');
        return $article;
    }

    public function getArticleById($id, bool $isDraft = false)
    {
        return $this->articleRepository->find($id, $isDraft);
    }

    public function updateArticleFromRequest($id, Request $request)
    {
        $fields = $request->validate([
            'title' => 'string',
            'thumbnail' => 'string',
            'excerpt' => 'string',
            'content' => 'string',
            'is_draft' => 'boolean',
            'categories' => 'array',
            'tags' => 'array',
        ]);

        $article = $this->articleRepository->update($id, $fields);
        $article->load('user');
        return $article;
    }

    public function destroyArticleById($id)
    {
        return $this->articleRepository->destroy($id);
    }
}
