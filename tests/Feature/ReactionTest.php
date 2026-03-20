<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reaction_to_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/posts/{$post->id}/reaction", [
                'type' => 'like'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Reaction updated successfully',
                'status' => 'added',
                'count' => 1,
                'reaction_type' => 'like'
            ]);

        $this->assertDatabaseHas('reactions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like'
        ]);
    }

    public function test_user_can_toggle_reaction_off()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        Reaction::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/posts/{$post->id}/reaction", [
                'type' => 'like'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Reaction updated successfully',
                'status' => 'removed',
                'count' => 0,
                'reaction_type' => null
            ]);

        $this->assertDatabaseMissing('reactions', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }

    public function test_user_cannot_react_with_invalid_type()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/posts/{$post->id}/reaction", [
                'type' => 'invalid-type'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }
    public function test_unauthenticated_user_cannot_react()
    {
        $post = Post::factory()->create();

        $response = $this->postJson("/api/posts/{$post->id}/reaction", [
            'type' => 'like'
        ]);

        $response->assertStatus(401);
    }
}
