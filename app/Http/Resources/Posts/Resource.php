<?php

namespace App\Http\Resources\Posts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'thumbnail' => $this->thumbnail,
            'views' => $this->views,
            'createdAt' => $this->created_at->format('d.m.Y H:i:s')
        ];
    }
}
