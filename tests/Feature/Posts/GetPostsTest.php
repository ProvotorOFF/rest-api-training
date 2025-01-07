<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetPostsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Post::factory(5)->for(User::factory())->create();
    }

    public function test_get_posts(): void
    {
        $response = $this->get(route('posts.index'));
        $response->assertOk();
        $response->assertJsonStructure([
            '*' => [
                'title', 'thumbnail', 'views', 'createdAt'
            ]
        ]);

        $publishedPostsCount = Post::where('status', 'published')->count();
        $response->assertJsonCount($publishedPostsCount);
    }
}
