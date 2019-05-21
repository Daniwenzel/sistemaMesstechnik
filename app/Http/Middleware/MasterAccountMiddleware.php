<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class MasterAccountMiddleware
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
        if ($request->user() && !($request->user()->hasAnyRole('Admin|Master'))) {
            return new Response(view('errors.403')->with('role', 'Admin'));
        }

        return $next($request);
    }
}
