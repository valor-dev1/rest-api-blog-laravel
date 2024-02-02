<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
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
        $title = fake()->sentence();
        return [
            // 'user_id' => optional(User::whereIn('role', [User::ADMIN, User::EDITOR])->inRandomOrder()->first())->id || 1,
            'title' => $title,
            'slug'  => Str::slug($title, '-'),
            'content'   => fake()->paragraph(10),
            'status'    => fake()->randomElement([Post::PENDING, Post::PUBLISH, Post::DRAFT]),
            'allow_comments' => fake()->randomElement([0, 1]),
        ];
    }
}
