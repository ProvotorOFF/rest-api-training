<?php

namespace Tests\Feature\Posts;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    
    public function setUp(): void {
        parent::setUp();
        $this->signIn();
    }

    public function test_create_post() {
        $file = UploadedFile::fake()->image('test.png', 100, 100)->size(100);
        $data = [
            'category_id' => Category::factory()->createOne()->id,
            'title' => fake()->sentence(),
            'body' => fake()->randomHtml(),
            'thumbnail' => $file,
            'status' => fake()->randomElement([
                PostStatus::Draft,
                PostStatus::Private,
                PostStatus::Published,
            ])->value,
            'views' => fake()->numberBetween(0, 1000),
        ];
        $response = $this->post(route('posts.store'), $data);
        $response->assertCreated();
        $response->assertJsonStructure([
            'title', 'body', 'views', 'createdAt', 'authorName', 'categoryName', 'comments'
        ]);
        $response->assertJson([
            'title' => $data['title'],
            'views' => $data['views'],
            'authorName' => $this->user->name,
            'categoryName' => Category::find($data['category_id'])->name,
            'comments' => [],
            'createdAt' => true,
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => $data['title'],
            'views' => $data['views'],
            'user_id' => $this->user->id,
            'category_id' => $data['category_id'],
        ]);
    }

    public function test_create_post_failed_validation() {
        $data = [
            'body' => fake()->randomHtml(),
        ];
        $response = $this->post(route('posts.store'), $data);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['title', 'status']);
    }
}
