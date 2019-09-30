<?php

namespace Messtechnik\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AdminAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && !($request->user()->hasRole('Admin'))) {
            return new Response(view('errors.403')->with('role', 'Admin'));
        }

        return $next($request);
    }
}
