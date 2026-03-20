<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function getAllPosts(?string $searchTerm = null, $limit = 7): LengthAwarePaginator
    {
        return Post::with([
            'user',
            'comments.user',
            'reactions' => fn($q) => $q->where('user_id', Auth::id()),
        ])
            ->withCount(['reactions', 'comments'])
            ->when(
                $searchTerm,
                fn($query, $searchTerm) =>
                $query->where(
                    fn($q) =>
                    $q->where('title', 'like', "%{$searchTerm}%")
                        ->orWhere('content', 'like', "%{$searchTerm}%")
                )
            )
            ->orderByDesc('id')
            ->paginate($limit);
    }

    public function getMyPosts($limit = 7): LengthAwarePaginator
    {
        return Post::with([
            'user',
            'comments.user',
            'reactions' => fn($q) => $q->where('user_id', Auth::id()),
        ])
            ->withCount(['reactions', 'comments'])
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->paginate($limit);
    }

    public function getComments(Post $post, $limit = 10): LengthAwarePaginator
    {
        return $post->comments()
            ->with('user')
            ->orderByDesc('id')
            ->paginate($limit);
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
