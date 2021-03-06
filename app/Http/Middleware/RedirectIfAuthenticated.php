<?php

namespace App\Http\Middleware;

use App\Services\AuthServices\AuthService;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            /* @var User $user */
            $user = Auth::user();
            return redirect((new AuthService())->resolveRedirectionUrl($user));
        }

        return $next($request);
    }
}
