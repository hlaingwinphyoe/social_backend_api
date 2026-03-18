<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence;
        $content = fake()->paragraphs(5, true);

        return [
            'user_id' => User::all()->random()->id,
            'title' => $title,
            'content' => $content,
        ];
    }
}
