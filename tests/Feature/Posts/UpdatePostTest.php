<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{


    protected Post $post;

    public function setUp(): void {
        parent::setUp();
        $this->signIn();
        $this->post = Post::factory()->createOne(['user_id' => $this->user->id]);
    }

    public function test_update_post() {
        $data = [
            'title' => 'New title',
        ];
        $response = $this->patch(route('posts.update', ['post' => $this->post->id]), $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'title', 'body', 'views', 'createdAt', 'authorName', 'categoryName', 'comments'
        ]);
        $response->assertJson([
            'title' => $data['title'],
        ]);
        $this->assertDatabaseHas('posts', [
            'id' => $this->post->id,
            'title' => $data['title'],
        ]);
    }
}
