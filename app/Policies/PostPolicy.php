<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use LDAP\Result;

class PostPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(?User $user, string $ability): bool|null
    {
        if ($user?->isAdmin()) {
            return true;
        }
    
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->isAdmin() || $user->isEditor()
                ? Response::allow()
                : Response::deny(__('messages.posts.not_allowed_create'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): Response
    {
        return $user->isAdmin() || $user->isEditor() || $post->user_id === $user->id
                ? Response::allow()
                : Response::deny(__('messages.posts.not_allowed_update'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): Response
    {
        return $user->isAdmin()
                ? Response::allow()
                : Response::deny(__('messages.posts.not_allowed_delete'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): Response
    {
        return $user->isAdmin()
                ? Response::allow()
                : Response::deny(__('messages.posts.not_allowed_restore'));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): Response
    {
        return $user->isAdmin()
                ? Response::allow()
                : Response::deny(__('messages.posts.not_allowed_force_delete'));
    }
}
