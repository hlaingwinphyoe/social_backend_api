<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function getAllPosts(?string $searchTerm = null): Collection
    {
        return Post::with('user', 'comments')
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%");
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getMyPosts(): Collection
    {
        return Post::with('user', 'comments')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
    }

    public function createPost(array $data): Post
    {
        $data['user_id'] = Auth::id();

        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('posts', 'public');
        }

        return Post::create($data);
    }

    public function updatePost(Post $post, array $data): Post
    {
        if (isset($data['image'])) {
            Storage::disk('public')->delete($post->image);
            $data['image'] = $data['image']->store('posts', 'public');
        }

        $post->update($data);

        return $post;
    }

    public function deletePost(Post $post): void
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->comments()->delete();

        $post->delete();
    }

    public function createComment(Post $post, array $data)
    {
        return $post->comments()->create($data);
    }
}
