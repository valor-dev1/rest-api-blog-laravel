<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
    
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->isAdmin() || $user->isEditor()
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed', ['action' => 'view']));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $user->isAdmin() || $user->isEditor() || $user->id === $model->id
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed', ['action' => 'update']));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->isAdmin()
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed', ['action' => 'create']));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return $user->isAdmin() || $user->id === $model->id
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed', ['action' => 'update']));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $user->isAdmin()
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed', ['action' => 'delete']));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): Response
    {
        return $user->isAdmin()
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed', ['action' => 'restore']));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): Response
    {
        return $user->isAdmin()
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed', ['action' => 'forcefully delete']));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function viewPosts(User $user, User $model): Response
    {
        return $user->isAdmin() || $user->isEditor()
                ? Response::allow()
                : Response::deny(__('messages.users.not_allowed_vieW_posts', ['user' => $model->name]));
    }
}
