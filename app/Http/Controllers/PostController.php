<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\StoreRequest;
use App\Http\Requests\Posts\UpdateRequest;
use App\Http\Resources\Posts\DetailResource;
use App\Http\Resources\Posts\Resource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Support\Facades\Request;

class PostController extends Controller
{

    public function __construct(protected PostService $postService)
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->authorizeResource(Post::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Resource::collection($this->postService->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return new DetailResource($this->postService->createWithImage($request));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new DetailResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {
        $post->update($request->validated());
        return new DetailResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        return $post->delete();
    }
}
