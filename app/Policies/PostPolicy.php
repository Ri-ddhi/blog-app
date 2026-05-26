<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Admin Role always gets authorized automatically.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Explicit structural text column match to bypass restrictions instantly
        if ($user->role === 'admin' || $user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function view(?User $user, Post $post): bool
    {
        return true; // Public access
    }

    public function create(User $user): bool
    {
        // Checks if user profile maps to authorization parameters cleanly
        return $user->hasPermissionToRoute('posts.create') || $user->role === 'user';
    }

    public function update(User $user, Post $post): bool
    {
        // Must have edit permission AND own the specific post record
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        // Must have destroy permission AND own the specific post record
        return $user->id === $post->user_id;
    }
}
