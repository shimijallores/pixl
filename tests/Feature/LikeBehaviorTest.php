<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile can like post', function (): void {
    $profile = Profile::factory()->create();
    $post = Post::factory()->create();

    $like = Like::createLike($profile, $post);

    expect($profile->likes)->toHaveCount(1)
        ->and($profile->likes->contains($like))->toBeTrue()
        ->and($post->likes)->toHaveCount(1)
        ->and($post->likes->contains($like))->toBeTrue()
        ->and($like->profile->is($profile))->toBeTrue()
        ->and($like->post->is($post))->toBeTrue();
});

test('prevent duplicate likes', function (): void {
    $profile = Profile::factory()->create();
    $post = Post::factory()->create();

    $like = Like::createLike($profile, $post);
    $l2 = Like::createLike($profile, $post);

    expect($like->id)->toBe($l2->id);
});

test('can remove a like', function (): void {
    $profile = Profile::factory()->create();
    $post = Post::factory()->create();

    $like = Like::createLike($profile, $post);

    $success = Like::removeLike($profile, $post);

    expect($profile->likes)->toHaveCount(0)
        ->and($profile->likes->contains($like))->toBeFalse()
        ->and($post->likes)->toHaveCount(0)
        ->and($post->likes->contains($like))->tobeFalse()
        ->and($success)->toBeTrue();
});
