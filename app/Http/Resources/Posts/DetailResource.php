<?php

namespace App\Http\Resources\Posts;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @mixin \App\Models\Post
 */
class DetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    { 
        $this->load(['comments.user']);
        return [
            'title' => $this->title,
            'body' => $this->body,
            'views' => $this->views,
            'authorName' => $this->user->name,
            'createdAt' => $this->created_at,
            'categoryName' => $this->category ? $this->category->name : '',
            'comments' => CommentResource::collection($this->comments)
        ];
    }
}
