<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostUnitTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test profile route.
     *
     * @return void
     */
    public function testProfile()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')
            ->get('/profile')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    // other user data assertions
                ],
            ]);
    }

    /**
     * Test user posts route.
     *
     * @return void
     */
    public function testUserPosts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'sanctum')
            ->get("/users/{$user->id}/posts")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $post->id,
                        // other post data assertions
                    ],
                ],
            ]);
    }

    /**
     * Test user index route.
     *
     * @return void
     */
    public function testUserIndex()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->get('/users')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email'],
                ],
            ]);
    }

    /**
     * Test store user route.
     *
     * @return void
     */
    public function testStoreUser()
    {
        $admin = User::factory()->create(['role' => User::ADMIN]);
        $userData = User::factory()->make()->toArray();

        $this->actingAs($admin, 'sanctum')
            ->post('/users', $userData)
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => __('messages.users.created_successfully'),
                'user' => [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                ],
            ]);
    }

    /**
     * Test show user route.
     *
     * @return void
     */
    public function testShowUser()
    {
        $admin = User::factory()->create(['role' => User::ADMIN]);
        $user = User::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->get("/users/{$user->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    // other user data assertions
                ],
            ]);
    }

    /**
     * Test update user route.
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $admin = User::factory()->create(['role' => User::ADMIN]);
        $user = User::factory()->create();
        $updatedData = ['name' => 'Updated Name'];

        $this->actingAs($admin, 'sanctum')
            ->put("/users/{$user->id}", $updatedData)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => __('messages.users.updated_successfully'),
                'user' => [
                    'name' => 'Updated Name',
                ],
            ]);
    }

    /**
     * Test delete user route.
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $admin = User::factory()->create(['role' => User::ADMIN]);
        $user = User::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->delete("/users/{$user->id}")
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => __('messages.users.deleted_successfully'),
            ]);
    }

    /**
     * Test comment index route.
     *
     * @return void
     */
    public function testCommentIndex()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->get("/posts/{$post->id}/comments")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $comment->id,
                        // other comment data assertions
                    ],
                ],
            ]);
    }

    /**
     * Test show comment route.
     *
     * @return void
     */
    public function testShowComment()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->get("/posts/{$post->id}/comments/{$comment->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $comment->id,
                    // other comment data assertions
                ],
            ]);
    }

    /**
     * Test store comment route.
     *
     * @return void
     */
    public function testStoreComment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $commentData = Comment::factory()->make()->toArray();

        $this->actingAs($user, 'sanctum')
            ->post("/posts/{$post->id}/comments", $commentData)
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => __('messages.comments.created_successfully'),
                'comment' => [
                    'comment' => $commentData['comment'],
                ],
            ]);
    }

    /**
     * Test update comment route.
     *
     * @return void
     */
    public function testUpdateComment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
        $updatedData = ['comment' => 'Updated Comment'];

        $this->actingAs($user, 'sanctum')
            ->put("/posts/{$post->id}/comments/{$comment->id}", $updatedData)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => __('messages.comments.updated_successfully'),
                'comment' => [
                    'comment' => 'Updated Comment',
                ],
            ]);
    }

    /**
     * Test delete comment route.
     *
     * @return void
     */
    public function testDeleteComment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $this->actingAs($user, 'sanctum')
            ->delete("/posts/{$post->id}/comments/{$comment->id}")
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => __('messages.comments.deleted_successfully'),
            ]);
    }

    /**
     * Test register route.
     *
     * @return void
     */
    public function testRegister()
    {
        $userData = User::factory()->make()->toArray();

        $this->post('/register', $userData)
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'user' => [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                ],
            ]);
    }

    /**
     * Test login route.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ]);
    }

}