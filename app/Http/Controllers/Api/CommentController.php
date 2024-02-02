<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

/**
 * Class CommentController
 * @package App\Http\Controllers\Api
 *
 * Controller for managing comments on posts.
 */
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Post $post)
    {
        // Return a collection of comments for the specified post
        return CommentResource::collection($post->comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        // Create a new comment for the specified post
        $comment = $post->comments()->create($request->validated());

        // Return success response with the created comment resource
        return response()->json([
            'success'   => true,
            'message'   => __('messages.comments.created_successfully'),
            'comment'   => new CommentResource($comment)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \App\Http\Resources\CommentResource
     */
    public function show(Comment $comment)
    {
        // Return the specified comment resource
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Post  $post
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCommentRequest $request, Post $post, Comment $comment)
    {
        // Authorize the current user to update the comment
        Gate::authorize('update', $comment);

        // Update the comment with validated request data
        $comment->update($request->validated());

        // Return success response with the updated comment resource
        return response()->json([
            'success'   => true,
            'message'   => __('messages.comments.updated_successfully'),
            'comment'   => new CommentResource($comment)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post, Comment $comment)
    {
        // Authorize the current user to delete the comment
        Gate::authorize('delete', $comment);

        // Delete the specified comment
        $comment->delete();

        // Return success response
        return response()->json([
            'success'   => true,
            'message'   => __('messages.comments.deleted_successfully'),
        ]);
    }
}
