<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class PostTest extends TestCase
{
    protected $user;
    protected $otherUser;
    protected $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
        $this->post = Post::factory()->for($this->user)->create();

        Session::start();
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->otherUser->delete();
        $this->post->delete();

        parent::tearDown();
    }

    public function testPublicCanViewAllPosts()
    {
        $response = $this->getJson(route('posts.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'is_draft', 'published_at']
                ],
                'links',
                'meta'
            ]);
    }

    public function testPublicCanViewSinglePost()
    {
        $response = $this->getJson(route('posts.show', $this->post));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->post->id,
                    'title' => $this->post->title,
                ]
            ]);
    }

    public function testGuestCannotCreatePost()
    {
        $response = $this->postJson(route('posts.store'), [
            '_token' => csrf_token(),
            'title' => 'Unauthorized Post',
            'content' => 'Body',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testAuthenticatedUserCanCreatePost()
    {
        $response = $this->actingAs($this->user)
            ->postJson(
                route('posts.store'),
                [
                    '_token' => csrf_token(),
                    'title' => 'New Post',
                    'content' => 'Post content',
                    'is_draft' => true
                ]
            );

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Post',
                    'content' => 'Post content',
                    'is_draft' => true
                ]
            ]);
    }

    public function testOwnerCanUpdateTheirPost()
    {
        $response = $this->actingAs($this->user)
            ->putJson(
                route('posts.update', $this->post),
                [
                    '_token' => csrf_token(),
                    'title' => 'Updated Title',
                    'content' => 'Updated Body'
                ]
            );

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->post->id,
                    'title' => 'Updated Title',
                    'content' => 'Updated Body'
                ]
            ]);
    }

    public function testNonOwnerCannotUpdatePost()
    {
        $response = $this->actingAs($this->otherUser)
            ->putJson(
                route('posts.update', $this->post),
                [
                    '_token' => csrf_token(),
                    'title' => 'Hacked Title'
                ]
            );

        $response->assertStatus(403);
    }

    public function testOwnerCanDeleteTheirPost()
    {
        $response = $this->actingAs($this->user)
            ->deleteJson(
                route('posts.destroy', $this->post),
                ['_token' => csrf_token()]
            );

        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', ['id' => $this->post->id]);
    }

    public function testNonOwnerCannotDeletePost()
    {
        $response = $this->actingAs($this->otherUser)
            ->deleteJson(
                route('posts.destroy', $this->post),
                ['_token' => csrf_token()]
            );

        $response->assertStatus(403);
    }
}
