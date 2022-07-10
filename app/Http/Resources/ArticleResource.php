<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'thumbnail' => $this->thumbnail,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'user' => new UserResource($this->whenLoaded('user')),
            'is_draft' => $this->is_draft,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
