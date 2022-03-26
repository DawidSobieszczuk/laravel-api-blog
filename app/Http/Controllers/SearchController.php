<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;

class SearchController extends Controller
{
    public function category($slug)
    {
        return ArticleResource::collection(Article::with('user')->where('categories', 'like', '%"' . $slug . '"%')->paginate());
    }

    public function tag($slug)
    {
        return ArticleResource::collection(Article::with('user')->where('tags', 'like', '%"' . $slug . '"%')->paginate());
    }

    public function search($slug)
    {
        $articles = Article::with('user')->where('title', 'like', '%' . $slug . '%')
            ->orWhere('content', 'like', '%' . $slug . '%')
            ->orWhere('categories', 'like', '%"' . $slug . '"%')
            ->orWhere('tags', 'like', '%"' . $slug . '"%')
            ->paginate();

        return ArticleResource::collection($articles);
    }
}
