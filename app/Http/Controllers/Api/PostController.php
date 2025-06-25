<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $posts = Post::with('user')
            ->where('is_draft', false) // or make this a scope on the model if use it often
            ->paginate(20);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = $request->user()
            ->posts()
            ->create($request->validated());

        return new PostResource($post->load('user'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResource
    {
        abort_if(
            $post->is_draft || $post->published_at->isFuture(),
            404,
            'Post Not Found'
        );

        return new PostResource($post->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResource
    {
        // only the post's author can update the post
        Gate::denyIf(fn (User $user) => $user->id !== $post->user_id);
        // or use the policy like $this->authorize('update', $post); 

        $post->update($request->validated());

        return new PostResource($post->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        // only the post's author can delete the post
        Gate::denyIf(fn (User $user) => $user->id !== $post->user_id);
        // or use the policy like $this->authorize('delete', $post); 

        $post->delete();

        return response()
            ->json(['message' => 'Post deleted successfully']);
    }
}
