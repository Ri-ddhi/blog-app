<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->check() && !auth()->user()->is_active) {
            return redirect('/suspended')->withErrors([
                'account' => 'Your account has been suspended. Please contact support.',
            ]);
        }
        return $next($request);

    }
}
