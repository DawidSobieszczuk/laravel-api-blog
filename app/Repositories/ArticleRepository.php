<?php

namespace App\Repositories;

use App\Models\Article;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class ArticleRepository extends Repository
{
    public function __construct(Article $article)
    {
        $this->model = $article;
    }

    private function addDraftQuery($query, bool $isDraft)
    {
        return $query->when(
            !$isDraft,
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

    public function all(bool $isDraft = false)
    {
        $query = $this->model->with('user');
        $query = $this->addDraftQuery($query, $isDraft);
        return $query->get();
    }

    public function paginate(bool $isDraft = false, $perPage = null)
    {
        $query = $this->model->with('user');
        $query = $this->addDraftQuery($query, $isDraft);
        return $query->paginate($perPage);
    }

    public function create(array $input, $user = null)
    {
        if (!$user) return false;
        return $user->articles()->create($input);
    }

    public function find($id, bool $isDraft = false)
    {
        $query = $this->model->with('user');
        $query = $this->addDraftQuery($query, $isDraft);
        return $query->find($id);
    }

    public function search($slug, bool $isDraft = false, $perPage = null)
    {
        $query = $this->model->with('user');
        $query = $this->addDraftQuery($query, $isDraft);
        $query = $this->addSearchQuery($query, $slug);
        return $query->paginate($perPage);
    }

    public function searchByTag($slug, bool $isDraft = false, $perPage = null)
    {
        $query = $this->model->with('user');
        $query = $this->addDraftQuery($query, $isDraft);
        $query = $this->addSearchByTagQuery($query, $slug);
        return $query->paginate($perPage);
    }

    public function searchByCategory($slug, bool $isDraft = false, $perPage = null)
    {
        $query = $this->model->with('user');
        $query = $this->addDraftQuery($query, $isDraft);
        $query = $this->addSearchByCategoryQuery($query, $slug);
        return $query->paginate($perPage);
    }
}
