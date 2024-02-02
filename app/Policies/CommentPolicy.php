<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): Response
    {
        return $user->isAdmin() || $user->id == $comment->user->id
                ? Response::allow()
                : Response::deny(__('messages.comments.not_allowed_update'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): Response
    {
        return $user->isAdmin() || $user->id == $comment->user->id
                ? Response::allow()
                : Response::deny(__('messages.comments.not_allowed_delete'));
    }

}
