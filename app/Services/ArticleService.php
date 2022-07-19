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

    public function getAllArticles($allowDraft)
    {
        return $this->articleRepository->all($allowDraft);
    }

    public function getAllArticlesPaginate($allowDraft, $perPage = null)
    {
        return $this->articleRepository->paginate($allowDraft, $perPage);
    }

    public function getArticlesByCategory($categoryName, $allowDraft)
    {
        return $this->articleRepository->searchByCategory($categoryName, $allowDraft);
    }

    public function getArticlesByCategoryPaginate($categoryName, $allowDraft, $perPage = null)
    {
        return $this->articleRepository->searchByCategoryPaginate($categoryName, $allowDraft, $perPage);
    }

    public function getArticlesByTag($tagName, $allowDraft)
    {
        return $this->articleRepository->searchByTag($tagName, $allowDraft);
    }

    public function getArticlesByTagPaginate($tagName, $allowDraft, $perPage = null)
    {
        return $this->articleRepository->searchByTagPagiante($tagName, $allowDraft, $perPage);
    }

    public function searchArticles($slug, $allowDraft)
    {
        return $this->articleRepository->search($slug, $allowDraft);
    }

    public function searchArticlesPaginate($slug, $allowDraft, $perPage = null)
    {
        return $this->articleRepository->searchPaginate($slug, $allowDraft, $perPage);
    }

    public function getArticleById($id, $allowDraft)
    {
        return $this->articleRepository->find($id, $allowDraft);
    }

    public function createNewArticleFromRequest(Request $request, $author)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'thumbnail' => 'required|string',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'categories' => 'array',
            'tags' => 'array',
        ]);

        $fields['is_draft'] = true;
        $fields['categories'] = $fields['categories'] ?? [];
        $fields['tags'] = $fields['tags'] ?? [];

        $article = $this->articleRepository->create($fields, $author);
        return $article;
    }

    public function updateArticleFromRequest($id, Request $request)
    {
        $fields = $request->validate([
            'title' => 'string',
            'thumbnail' => 'string',
            'excerpt' => 'string',
            'content' => 'string',
            'categories' => 'array',
            'tags' => 'array',
        ]);

        $article = $this->articleRepository->update($id, $fields);
        return $article;
    }

    public function publishArticle($id)
    {
        return $this->articleRepository->update($id, ['is_draft' => false]);
    }

    public function unpublishArticle($id)
    {
        return $this->articleRepository->update($id, ['is_draft' => true]);
    }

    public function destroyArticleById($id)
    {
        return $this->articleRepository->destroy($id);
    }
}
