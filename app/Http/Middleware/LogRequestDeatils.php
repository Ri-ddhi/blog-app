<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Laravel's built-in logger
use Symfony\Component\HttpFoundation\Response;

class LogRequestDetails
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log::info() writes a line to storage/logs/laravel.log
        // We build an array of info we want to save
        Log::info('Post Route Accessed', [
            // $request->method() returns 'GET', 'POST', 'PUT', 'DELETE', etc.
            'method' => $request->method(),

            // $request->fullUrl() returns the complete URL, e.g. http://localhost/posts/my-slug
            'url' => $request->fullUrl(),

            // auth()->id() returns the logged-in user's ID, or null if not logged in
            'user_id' => auth()->id(),

            // now() is a Laravel helper that returns the current date + time
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Always let the request continue — this middleware only logs, it doesn't block
        return $next($request);
    }
}
