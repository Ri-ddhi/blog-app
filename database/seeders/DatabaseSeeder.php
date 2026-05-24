<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $categories = Category::factory()->count(10)->create();
        // User::factory(10)->create();

        User::factory()->count(3)->create(['role' => 'admin'])
        ->each(function($user) {
            Post::factory($user)->count(rand(2, 5))
                ->create(['user_id' => $user->id]);

        });

        User::factory()
            ->count(10)
            ->create(['role' => 'user'])
            ->each(function ($user) {
                Post::factory()
                    ->count(rand(2, 5))
                    ->create(['user_id' => $user->id]);
            });

}
}
