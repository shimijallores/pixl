<?php

use App\Models\Profile;
use App\Models\Follow;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile cannot follow itself', function () {
    $profile = Profile::factory()->create();

    expect(fn() => Follow::createFollow($profile, $profile))
        ->toThrow(InvalidArgumentException::class, 'A profile cannot follow itself.');
});

test('profile can follow another profile', function () {
    $follower = Profile::factory()->create();
    $following = Profile::factory()->create();

    $follow = Follow::createFollow($follower, $following);

    expect($following->followers->contains($follower))->toBeTrue()
        ->and($follower->following->contains($following))->toBeTrue();
});

test('profile can remove a follow', function () {
    $follower = Profile::factory()->create();
    $following = Profile::factory()->create();

    $follow = Follow::createFollow($follower, $following);
    $success = Follow::removeFollow($follower, $following);

    expect($following->followers->contains($follower))->toBeFalse()
        ->and($follower->following->contains($following))->toBeFalse()
        ->and($success)->toBeTrue()
        ->and($follow->fresh())->toBeNull();
});

