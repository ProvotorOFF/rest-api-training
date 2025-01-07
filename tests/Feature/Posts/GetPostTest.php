<?php

namespace Tests\Feature\Posts;

use App\Http\Resources\CommentResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetPostTest extends TestCase
{

    use RefreshDatabase;

    protected Post $activePost;
    protected Post $draftPost;

    public function setUp(): void {
        parent::setUp();

        $postFactory = Post::factory()
        ->for(User::factory())
        ->for(Category::factory())
        ->has(Comment::factory(3)->for(User::factory()));

        $this->activePost = $postFactory->createOne(['status' => 'published']);
        $this->draftPost = $postFactory->createOne(['status' => 'draft']);
    }
   

    public function test_get_active_post() {
        $response = $this->get(route('posts.show', ['post' => $this->activePost->id]));
        $response->assertOk();
        $response->assertJsonStructure(['title', 'body', 'views', 'authorName', 'createdAt', 'categoryName', 'comments' => [
            '*' => [
                'userName', 'text'
            ]
        ]]);
        $response->assertJson([
            'title' => $this->activePost->title,
            'body' => $this->activePost->body,
            'views' => $this->activePost->views,
            'authorName' => $this->activePost->user->name,
            'createdAt' => $this->activePost->created_at->toJSON(),
            'categoryName' => $this->activePost->category->name,
            'comments' => CommentResource::collection($this->activePost->comments)->resolve()
        ]);
    }

    public function test_get_draft_post() {
        $response = $this->get(route('posts.show', ['post' => $this->draftPost->id]));
        $response->assertBadRequest();
        $response->assertJsonStructure(['message']);
        $response->assertJson(['message' => 'This action is unauthorized.']);
    }

    
}
