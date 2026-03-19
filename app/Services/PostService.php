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
        return Post::with(['user', 'comments'])
            ->withCount(['reactions', 'comments'])
            ->with(['reactions' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%");
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getMyPosts(): Collection
    {
        return Post::with(['user', 'comments'])
            ->withCount(['reactions', 'comments'])
            ->with(['reactions' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
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

    public function toggleReaction(Post $post, array $data)
    {
        $userId = Auth::id();
        $type = $data['type'];

        $reaction = $post->reactions()->where('user_id', $userId)->first();

        if ($reaction) {
            $reaction->delete();
            $status = 'removed';
        } else {
            $post->reactions()->create([
                'user_id' => $userId,
                'type' => $type
            ]);
            $status = 'added';
        }

        return [
            'status' => $status,
            'count' => $post->reactions()->count(),
            'reaction_type' => $status === 'removed' ? null : $type
        ];
    }
}
