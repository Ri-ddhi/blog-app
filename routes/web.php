<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;

// PUBLIC ROUTES — anyone can visit these, no login required
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// GUEST-ONLY ROUTES — redirect away if already logged in
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// AUTH-ONLY ROUTES — must be logged in AND account must be active
// We apply TWO middleware here:
//   'auth'   = built-in Laravel middleware, checks if user is logged in
//   'active' = our custom middleware, checks if user->is_active == true
Route::middleware(['auth', 'active'])->group(function () {

    // log.requests runs on every request inside this group
    // It writes to laravel.log so we can see who accessed what
    Route::middleware(['log.requests'])->group(function () {

        // Route::resource() automatically creates 7 routes:
        // GET    /posts           => index
        // GET    /posts/create    => create
        // POST   /posts           => store
        // GET    /posts/{post}    => show
        // GET    /posts/{post}/edit => edit
        // PUT    /posts/{post}    => update
        // DELETE /posts/{post}    => destroy
        // ->except() removes the ones we already defined as public above
        Route::resource('posts', PostController::class)->except(['index', 'show']);
    });

    // Logout: POST only (using a form with @csrf token, not a link)
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        Auth::logout();                       // clears the user session
        $request->session()->invalidate();    // destroys the session data
        $request->session()->regenerateToken(); // prevents CSRF token reuse
        return redirect('/');
    })->name('logout');
});

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Page shown to suspended users
// 'auth' makes sure only logged-in users see it (guests get /login instead)
Route::middleware(['auth'])->get('/suspended', function () {
    return view('suspended');
})->name('suspended');

// LIFECYCLE TEST — Task 1.4
// A simple closure route (no controller needed)
// Returns JSON with PHP version and current timestamp
Route::get('/lifecycle-test', function () {
    return response()->json([
        'php_version' => phpversion(),         // e.g. "8.3.0"
        'timestamp'   => now()->toDateTimeString(), // e.g. "2025-05-25 10:30:00"
    ]);
});
