<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    protected Post $post;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
        $this->post = Post::factory()->createOne(['user_id' => $this->user->id]);
    }

    public function test_delete_post()
    {
        $response = $this->delete(route('posts.destroy', ['post' => $this->post->id]));
        $response->assertOk();
        $this->assertDatabaseMissing('posts', [
            'id' => $this->post->id,
        ]);
    }
}
