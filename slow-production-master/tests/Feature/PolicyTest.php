<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_post()
    {
        $this->followingRedirects()
            ->assertGuest()
            ->post('/posts');

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_guest_cannot_like_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);

        $this->followingRedirects()
            ->assertGuest()
            ->post("/posts/$post->id/like");

        $this->assertDatabaseCount('post_likes', 0);
    }

    public function test_user_can_like_other_users_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post  = Post::factory()->create([
            'user_id' => $user2->id
        ]);

        $this->followingRedirects()
            ->actingAs($user1)
            ->post("/posts/$post->id/like")
            ->assertSuccessful();
    }

    public function test_user_can_delete_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);

        $this->followingRedirects()
            ->actingAs($user)
            ->delete("posts/$post->id")
            ->assertSuccessful();
    }

    public function test_user_cannot_delete_other_users_post()
    {
        $user  = User::factory()->create();
        $user2 = User::factory()->create();
        $post  = Post::factory()->create([
            'user_id' => $user->id
        ]);

        $this->followingRedirects()
            ->actingAs($user2)
            ->delete("posts/$post->id")
            ->assertForbidden();
    }

    public function test_moderator_can_delete_other_users_post()
    {
        $moderator = User::factory()->create([
            'role' => 'moderator'
        ]);

        $user2 = User::factory()->create();
        $post  = Post::factory()->create([
            'user_id' => $user2->id
        ]);

        $this->followingRedirects()
            ->actingAs($moderator)
            ->delete("posts/$post->id")
            ->assertSuccessful();
    }

}
