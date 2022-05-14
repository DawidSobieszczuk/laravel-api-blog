<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends ApiController
{
    private function is_user_admin()
    {
        return auth('sanctum')->user() ? auth('sanctum')->user()->is_admin : false;
    }

    public function index(Request $request)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $fields['per_page'] = (int)($fields['per_page'] ?? null);
        $fields['per_page'] = max($fields['per_page'], 0);

        return ArticleResource::collection(Article::with('user')->when(!$this->is_user_admin(), function ($query) {
            $query->where('is_draft', false);
        })->paginate($fields['per_page']));
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'is_draft' => 'boolean',
            'categories' => 'array',
            'tags' => 'array',
        ]);

        $fields['is_draft'] = $fields['is_draft'] ?? true;
        $fields['categories'] = $fields['categories'] ?? [];
        $fields['tags'] = $fields['tags'] ?? [];

        $article = $request->user()->articles()->create($fields);

        $article->load('user');
        return new ArticleResource($article);
    }

    public function show($id)
    {
        $article = Article::with('user')->when(!$this->is_user_admin(), function ($query) {
            $query->where('is_draft', false);
        })->find($id);

        if (!$article) {
            return $this->responseNotFound();
        }

        return new ArticleResource($article);
    }

    public function update(Request $request, $id)
    {
        $article = Article::with('user')->find($id);

        if (!$article) {
            return $this->responseNotFound();
        }

        $fields = $request->validate([
            'title' => 'string',
            'content' => 'string',
            'is_draft' => 'boolean',
            'categories' => 'array',
            'tags' => 'array',
        ]);

        $article->update($fields);
        return new ArticleResource($article);
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return $this->responseNotFound();
        }

        $article->delete();
        return $this->responseMessage('Destroyed.');
    }
}
