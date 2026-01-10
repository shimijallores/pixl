<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/dev/login', function () {
   $user = User::inRandomOrder()->first();

   Auth::login($user);

   request()->session()->regenerate();

   return redirect()->intended(route('profiles.show', $user->profile));
})->name('login');

Route::get('/dev/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->intended('/home');
});

Route::middleware(['auth'])->group(function () {
   Route::get('/home', [PostController::class, 'index'])->name('posts.index');
   Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::scopeBindings()->group(function () {
        Route::post('/{profile:handle}/status/{post}/reply', [PostController::class, 'reply'])->name('posts.reply');

        Route::post('/{profile:handle}/status/{post}/repost', [PostController::class, 'repost'])->name('posts.repost');

        Route::post('/{profile:handle}/status/{post}/quote', [PostController::class, 'quote'])->name('posts.quote');

        Route::post('/{profile:handle}/status/{post}/destroy', [PostController::class, 'destroy'])->name('posts.destroy');

        Route::post('/{profile:handle}/status/{post}/like', [PostController::class, 'like'])->name('posts.like');

        Route::post('/{profile:handle}/status/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
    });

    Route::post('/{profile.handle}/follow', [ProfileController::class, 'follow'])->name('profile.follow');

    Route::post('/{profile.handle}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');
});

Route::get('/{profile:handle}', [ProfileController::class, 'show'])->name('profiles.show');
Route::get('/{profile:handle}/replies', [ProfileController::class, 'replies'])->name('profiles.replies');

Route::scopeBindings()->group(function () {
    Route::get('/{profile:handle}/status/{post}', [PostController::class, 'show'])->name('posts.show');
});
