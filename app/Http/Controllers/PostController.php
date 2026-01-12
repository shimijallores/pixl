<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use App\Queries\PostThreadQuery;
use App\Queries\TimelineQuery;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $profile = Auth::user()->profile;

        $posts = TimelineQuery::forViewer($profile)->get();

        return view('posts.index', ['profile' => $profile, 'posts' => $posts]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile, Post $post): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $post = PostThreadQuery::for($post, Auth::user()?->profile)->load();

        return view('posts.show', ['post' => $post]);
    }

    public function store(CreatePostRequest $createPostRequest): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $profile = Auth::user()->profile;

        Post::publish($profile, $createPostRequest->content);

        return redirect(route('posts.index'));
    }

    public function reply(Profile $profile, Post $post, CreatePostRequest $createPostRequest)
    {
        $currentProfile = Auth::user()->profile;

        Post::reply($currentProfile, $post, $createPostRequest->content);

        return redirect()->intended(route('posts.index'));
    }

    public function repost(Profile $profile, Post $post)
    {
        $currentProfile = Auth::user()->profile;

        Post::repost($currentProfile, $post);

        return redirect()->intended(route('posts.index'));
    }

    public function quote(Profile $profile, Post $post, CreatePostRequest $createPostRequest)
    {
        $currentProfile = Auth::user()->profile;

        Post::repost($currentProfile, $post, $createPostRequest->content);

        return redirect()->intended(route('posts.index'));
    }

    public function like(Profile $profile, Post $post, CreatePostRequest $createPostRequest)
    {
        $currentProfile = Auth::user()->profile;

        $like = Like::createLike($currentProfile, $post);

        return response()->json(['like' => $like]);
    }

    public function unlike(Profile $profile, Post $post, CreatePostRequest $createPostRequest)
    {
        $currentProfile = Auth::user()->profile;

        $success = Like::removeLike($currentProfile, $post);

        return response()->json(['success' => $success]);
    }

    public function destroy(Profile $profile, Post $post)
    {
        $currentProfile = Auth::user()->profile;
        $success = false;

        if ($currentProfile->id === $profile->id) {
            $success = $post->delete() > 0;

            return response()->json(['success' => $success]);
        }

        $repost = $post->reposts()->where('profile_id', $currentProfile->id)->first();

        if (! is_null($repost)) {
            $success = $repost->delete() > 0;

            return response()->json(['success' => $success]);
        }

        return response()->json(['success' => $success]);
    }
}
