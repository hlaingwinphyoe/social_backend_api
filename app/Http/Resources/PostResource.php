<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'reactions_count' => $this->reactions_count ?? 0,
            'comments_count' => $this->comments_count ?? 0,
            'is_reacted' => $this->relationLoaded('reactions') ? $this->reactions->isNotEmpty() : false,
            'created_at' => $this->created_at->shortRelativeDiffForHumans(),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
