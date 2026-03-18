<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->input('q');

        $posts = $this->postService->getAllPosts($searchTerm);

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'posts' => PostResource::collection($posts),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $post = $this->postService->createPost($data);

            return response()->json([
                'message' => 'Post created successfully',
                'post' => new PostResource($post),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Post creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();
        try {
            $post = $this->postService->updatePost($post, $data);

            return response()->json([
                'message' => 'Post updated successfully',
                'post' => new PostResource($post),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Post update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        Gate::authorize('delete', $post);
        $this->postService->deletePost($post);

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
