<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;

// 1. PUBLIC ROUTES (Anyone can visit these)
Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');


// 2. GUEST-ONLY ROUTES (Logged-in users cannot access these)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// 3. AUTH-ONLY ROUTES (Only logged-in users can access these)
Route::middleware(['auth'])->group(function () {

    // We exclude 'index' and 'show' because they are already defined above as public routes
    Route::resource('posts', PostController::class)->except(['index', 'show']);

    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


// Lifecycle test route
Route::get('/lifecycle-test', function () {
    return response()->json([
        'php_version' => phpversion(),
        'timestamp' => now()->toDateTimeString(),
    ]);
});
