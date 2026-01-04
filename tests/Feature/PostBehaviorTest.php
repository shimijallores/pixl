<?php

use App\Models\Post;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('allows a profile to publish a post', function () {
    $profile = Profile::factory()->create();

    $post = Post::publish($profile, "Lorem Ipsum");

    expect($post->exists)->toBeTrue()
        ->and($post->profile->is($profile))->toBeTrue()
        ->and($post->parent_id)->toBeNull()
        ->and($post->reply_of_id)->toBeNull();
});

test('can reply to post', function () {
    $original = Post::factory()->create();

    $replier = Profile::factory()->create();
    $reply = Post::reply($replier, $original, 'reply content');

    expect($reply->parent->is($original))->toBeTrue()
        ->and($original->replies)->toHaveCount(1);
});

test('can have many replies', function () {
    $original = Post::factory()->create();
    $replies = Post::factory(5)->reply($original)->create();

    expect($replies->first()->parent->is($original))->toBeTrue()
        ->and($original->replies)->toHaveCount(5)
        ->and($original->replies->contains($replies->first()))->toBeTrue();
});

test('create basic repost', function () {
    $original = Post::factory()->create();

    $reposter = Profile::factory()->create();
    $repost = Post::repost($reposter, $original);

    expect($repost->repostOf->is($original))->toBeTrue()
        ->and($original->reposts)->toHaveCount(1)
        ->and($repost->content)->toBeNull();
});

test('create quote repost', function () {
    $original = Post::factory()->create();
    $content = 'content';

    $reposter = Profile::factory()->create();
    $repost = Post::repost($reposter, $original, $content);

    expect($repost->repostOf->is($original))->toBeTrue()
        ->and($original->reposts)->toHaveCount(1)
        ->and($repost->content)->toBe('content');
});


test('can have many reposts', function () {
    $original = Post::factory()->create();
    $reposts = Post::factory(5)->repost($original)->create();

    expect($reposts->first()->repostOf->is($original))->toBeTrue()
        ->and($original->reposts)->toHaveCount(5)
        ->and($original->reposts->contains($reposts->first()))->toBeTrue();
});

test('prevent duplicate repost', function () {
   $original = Post::factory()->create();
   $profile = Profile::factory()->create();

   $r1 = Post::repost($profile, $original);
   $r2 = Post::repost($profile, $original);

   expect($r1->id)->toBe($r2->id);
});

test('remove a repost', function () {
    $original = Post::factory()->create();
    $profile = Post::factory()->repost($original)->create()->profile;

    $success = Post::removeRepost($profile, $original);

    expect($original->reposts)->toHaveCount(0)
        ->and($success)->toBeTrue();

});

