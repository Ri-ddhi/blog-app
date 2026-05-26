<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and is NOT active
        if (Auth::check() && !Auth::user()->is_active) {

            // ✅ Prevent infinite loop: Allow the request to pass if they are already heading to suspended or logout routes
            if ($request->routeIs('suspended') || $request->routeIs('logout')) {
                return $next($request);
            }

            // Redirect to the suspended route with an error message
            return redirect()->route('suspended')->withErrors([
                'account' => 'Your account has been suspended. Please contact support.',
            ]);
        }

        return $next($request);
    }
}
