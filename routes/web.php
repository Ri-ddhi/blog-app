<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\PostController;

use Illuminate\Support\Facades\Auth;


// 1. COMPLETELY PUBLIC ROUTES

Route::get('/', [PostController::class, 'index'])->name('posts.index');


Route::get('/suspended', function () {

    return view('suspended');

})->name('suspended');



// 2. GUEST ONLY

Route::middleware(['guest'])->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

    Route::post('/register', [AuthController::class, 'register']);

});



// 3. AUTHENTICATED ROUTES (All post management lives here now)

Route::middleware(['auth'])->group(function () {


    // Logout

    Route::post('/logout', function (\Illuminate\Http\Request $request) {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    })->name('logout');





    // Register standard resource routes under auth

    Route::middleware(['log.requests'])->group(function () {

        Route::resource('posts', PostController::class)->except(['index', 'show']);
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::put('/permissions/{role}', [PermissionController::class, 'update'])->name('permissions.update');

    });

    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');


});
