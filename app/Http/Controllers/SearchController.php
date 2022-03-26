<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function category(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $fields['per_page'] = (int)($fields['per_page'] ?? null);
        $fields['per_page'] = max($fields['per_page'], 0);

        return ArticleResource::collection(Article::with('user')->where('categories', 'like', '%"' . $slug . '"%')->paginate($fields['per_page']));
    }

    public function tag(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $fields['per_page'] = (int)($fields['per_page'] ?? null);
        $fields['per_page'] = max($fields['per_page'], 0);

        return ArticleResource::collection(Article::with('user')->where('tags', 'like', '%"' . $slug . '"%')->paginate($fields['per_page']));
    }

    public function search(Request $request, $slug)
    {
        $fields = $request->validate([
            'per_page' => 'int',
        ]);

        $fields['per_page'] = (int)($fields['per_page'] ?? null);
        $fields['per_page'] = max($fields['per_page'], 0);

        $articles = Article::with('user')->where('title', 'like', '%' . $slug . '%')
            ->orWhere('content', 'like', '%' . $slug . '%')
            ->orWhere('categories', 'like', '%"' . $slug . '"%')
            ->orWhere('tags', 'like', '%"' . $slug . '"%')
            ->paginate($fields['per_page']);

        return ArticleResource::collection($articles);
    }
}
