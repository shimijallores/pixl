<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use App\Queries\TimelineQuery;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Auth::user()->profile;

        $posts = TimelineQuery::forViewer($profile)->get();

        return view('posts.index', compact('profile', 'posts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile, Post $post)
    {
        $post->load([
            'replies' => fn($q) => $q
                ->withCount(['likes', 'replies', 'reposts'])
                ->with([
                    'profile',
                    'parent.profile',
                    'replies' => fn($q) => $q
                        ->withCount(['likes', 'replies', 'reposts'])
                        ->with(['profile', 'parent.profile'])
                        ->oldest()
                ])
                ->oldest()
        ])->loadCount(['likes', 'replies', 'reposts']);

        return view('posts.show', compact('post'));
    }

    public function store(CreatePostRequest $request)
    {
        $profile = Auth::user()->profile;

        $post = Post::publish($profile, $request->content);

        return redirect(route('posts.index'));
    }

    public function reply(Profile $profile, Post $post, CreatePostRequest $request)
    {
        $currentProfile = Auth::user()->profile;

        $post = Post::reply($currentProfile, $post, $request->content);

        return redirect()->intended(route('posts.index'));
    }

    public function repost(Profile $profile, Post $post)
    {
        $currentProfile = Auth::user()->profile;

        $post = Post::repost($currentProfile, $post);

        return redirect()->intended(route('posts.index'));
    }

    public function quote(Profile $profile, Post $post, CreatePostRequest $request)
    {
        $currentProfile = Auth::user()->profile;

        $post = Post::repost($currentProfile, $post, $request->content);

        return redirect()->intended(route('posts.index'));
    }

    public function like(Profile $profile, Post $post, CreatePostRequest $request)
    {
        $currentProfile = Auth::user()->profile;

        $like = Like::createLike($currentProfile, $post);

        return response()->json(compact('like'));
    }
}
