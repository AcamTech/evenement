<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAttendee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        return response('Unauthorized.', 401);
//        return $next($request);
    }
}