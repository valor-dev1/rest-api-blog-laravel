<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 *
 * Controller for managing users.
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Authorize the current user to view any user
        Gate::authorize('viewAny', [User::class]);

        // Return paginated user resources
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        // Authorize the current user to create a user
        Gate::authorize('create', [User::class]);

        // Create a new user
        $user = User::create($request->only(['name', 'email', 'password']));

        // Return success response with the created user resource
        return response()->json([
            'success'   => true,
            'message'   => __('messages.users.created_successfully'),
            'user'      => $user,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        // Authorize the current user to view the user
        Gate::authorize('view', $user);

        // Return the specified user resource
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Authorize the current user to update the user
        Gate::authorize('update', $user);

        // Update the user with validated request data
        $user->update($request->only(['name', 'email', 'password']));

        // Return success response with the updated user resource
        return response()->json([
            'success'   => true,
            'message'   => __('messages.users.updated_successfully'),
            'user'      => UserResource::make($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        // Authorize the current user to delete the user
        Gate::authorize('delete', $user);

        // Delete the specified user
        $user->delete();

        // Return success response
        return response()->json([
            'success'   => true,
            'message'   => __('messages.users.deleted_successfully'),
        ]);
    }

    /**
     * Display the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        // Return the authenticated user's profile
        return response()->json([
            'success' => true,
            'user' => UserResource::make($request->user()),
        ]);
    }

    /**
     * Display the posts of the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function posts(User $user)
    {
        // Authorize the current user to view the user's posts
        Gate::authorize('viewPosts', $user);

        // Return the posts of the specified user
        return PostResource::collection($user->posts);
    }
}
