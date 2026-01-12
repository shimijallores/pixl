<?php

use App\Models\Follow;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile cannot follow itself', function (): void {
    $profile = Profile::factory()->create();

    expect(fn (): \App\Models\Follow => Follow::createFollow($profile, $profile))
        ->toThrow(InvalidArgumentException::class, 'A profile cannot follow itself.');
});

test('profile can follow another profile', function (): void {
    $follower = Profile::factory()->create();
    $following = Profile::factory()->create();

    $follow = Follow::createFollow($follower, $following);

    expect($following->followers->contains($follower))->toBeTrue()
        ->and($follower->following->contains($following))->toBeTrue();
});

test('profile can remove a follow', function (): void {
    $follower = Profile::factory()->create();
    $following = Profile::factory()->create();

    $follow = Follow::createFollow($follower, $following);
    $success = Follow::removeFollow($follower, $following);

    expect($following->followers->contains($follower))->toBeFalse()
        ->and($follower->following->contains($following))->toBeFalse()
        ->and($success)->toBeTrue()
        ->and($follow->fresh())->toBeNull();
});
