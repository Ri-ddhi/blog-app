<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;

// 1. PUBLIC ROUTES (Anyone can visit "/", whether logged in or a guest)
Route::get('/', [PostController::class, 'index'])->name('posts.index');
//Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');


// 2. GUEST-ONLY ROUTES (Logged-in users are automatically redirected away from here)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// 3. AUTH-ONLY ROUTES (Only logged-in users can access these)
Route::middleware(['auth'])->group(function () {
    // This creates the routes for: create, store, edit, update, and destroy
    Route::resource('posts', PostController::class );

    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); // Redirect back to the blog home page after logging out
    })->name('logout');
});


// Lifecycle test route
Route::get('/lifecycle-test', function () {
    return response()->json([
        'php_version' => phpversion(),
        'timestamp' => now()->toDateTimeString(),
    ]);
});
