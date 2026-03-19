<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        $maxReactions = 10;

        while (Reaction::count() < $maxReactions) {
            Reaction::firstOrCreate([
                'user_id' => $users->random()->id,
                'post_id' => $posts->random()->id,
            ], [
                'type' => 'like',
            ]);
        }
    }
}
