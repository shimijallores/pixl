<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
