<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends ApiController
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $fields['per_page'] = (int)($fields['per_page'] ?? null);
        $fields['per_page'] = max($fields['per_page'], 0);

        $articles = null;

        if ($user && $user->is_admin) {
            $articles = Article::with('user')->paginate($fields['per_page']);
        } else {
            $articles = Article::where('is_draft', false)->with('user')->paginate($fields['per_page']);
        }

        return ArticleResource::collection($articles);
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
        $user = auth('sanctum')->user();
        $article = Article::with('user')->find($id);

        if (!$article) {
            return $this->responseNotFound();
        }

        if (!($user && $user->is_admin) && $article->is_draft) {
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
