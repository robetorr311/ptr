<?php

namespace App\Http\Middleware;

use Closure;

class DriverAuthenticate
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
        if (!(auth()->check() && auth()->user()->hasRole('driver'))) {
            return redirect()->to('/');
        }

        return $next($request);

        return $next($request);
    }
}
