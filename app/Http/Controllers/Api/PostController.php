<?php

namespace App\Http\Controllers\Api;

use App\Events\PostCreated;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

/**
 * Class PostController
 * @package App\Http\Controllers\Api
 *
 * Controller for managing posts.
 */
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $posts = Post::query();
        $user = auth()->user();

        // Filter posts based on user role
        if (optional($user)->isEditor()) {
            $posts->where(['user_id' => $user->id]);
        } else if (! optional($user)->isAdmin()) {
            $posts->published();
        }

        // Return paginated post resources
        return PostResource::collection($posts->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {
        // Authorize the current user to create a post
        Gate::authorize('create', Post::class);

        // Create a new post for the authenticated user
        $post = $request->user()->posts()->create($request->validated());

        // Trigger the PostCreated event
        event(new PostCreated($post));

        // Return success response with the created post resource
        return response()->json([
            'success'   => true,
            'message'   => __('messages.posts.created_successfully', ['title' => $post->title]),
            'post'      => PostResource::make($post)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \App\Http\Resources\PostResource
     */
    public function show(Post $post)
    {
        // Return the specified post resource
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // Authorize the current user to update the post
        Gate::authorize('update', $post);

        // Update the post with validated request data
        $post->update($request->validated());

        // Return success response with the updated post resource
        return response()->json([
            'success'   => true,
            'message'   => __('messages.posts.updated_successfully', ['title' => $post->title]),
            'post'      => PostResource::make($post)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        // Authorize the current user to delete the post
        Gate::authorize('delete', $post);

        // Delete the specified post
        $post->delete();

        // Return success response
        return response()->json([
            'success'   => true,
            'message'   => __('messages.posts.deleted_successfully', ['title' => $post->title]),
        ]);
    }
}
