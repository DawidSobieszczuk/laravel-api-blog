<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository extends Repository
{
    public function __construct(Article $article)
    {
        $this->model = $article;
    }

    private function addDraftQuery($query, bool $allowDraft)
    {
        return $query->when(
            !$allowDraft,
            function ($query) {
                $query->where('is_draft', false);
            }
        );
    }

    private function addSearchQuery($query, $slug)
    {
        return $query->where(function ($query) use ($slug) {
            $query->where('title', 'like', '%' . $slug . '%')
                ->orWhere('content', 'like', '%' . $slug . '%')
                ->orWhere('categories', 'like', '%"' . $slug . '"%')
                ->orWhere('tags', 'like', '%"' . $slug . '"%');
        });
    }

    private function addSearchByTagQuery($query, $slug)
    {
        return $query->where('tags', 'like', '%"' . $slug . '"%');
    }
    private function addSearchByCategoryQuery($query, $slug)
    {
        return $query->where('categories', 'like', '%"' . $slug . '"%');
    }

    public function all(bool $allowDraft = false)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        return $query->get();
    }

    public function paginate(bool $allowDraft = false, $perPage = null)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        return $query->paginate($perPage);
    }

    public function create(array $input, $user = null)
    {
        if (!$user) return false;
        return $user->articles()->create($input);
    }

    public function find($id, bool $allowDraft = false)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        return $query->find($id);
    }

    public function search($slug, bool $allowDraft = false)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        $query = $this->addSearchQuery($query, $slug);
        return $query->get();
    }

    public function searchPaginate($slug, bool $allowDraft = false, $perPage = null)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        $query = $this->addSearchQuery($query, $slug);
        return $query->paginate($perPage);
    }

    public function searchByTag($slug, bool $allowDraft = false)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        $query = $this->addSearchByTagQuery($query, $slug);
        return $query->get();
    }

    public function searchByTagPagiante($slug, bool $allowDraft = false, $perPage = null)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        $query = $this->addSearchByTagQuery($query, $slug);
        return $query->paginate($perPage);
    }

    public function searchByCategory($slug, bool $allowDraft = false, $perPage = null)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        $query = $this->addSearchByCategoryQuery($query, $slug);
        return $query->get();
    }

    public function searchByCategoryPaginate($slug, bool $allowDraft = false, $perPage = null)
    {
        $query = $this->model;
        $query = $this->addDraftQuery($query, $allowDraft);
        $query = $this->addSearchByCategoryQuery($query, $slug);
        return $query->paginate($perPage);
    }
}
