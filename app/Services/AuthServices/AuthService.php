<?php
namespace App\Services\AuthServices;

use App\User;

class AuthService
{
    public function resolveRedirectionUrl(User $user)
    {
        $route = false;

        if ($user->hasRole('owner')) {

            $route = route('owner.home');

        } else if ($user->hasRole('driver')) {

            $route = route('driver.home');

        } else if ($user->hasRole('admin')) {

            $route = route('admin.index');

        }

        return $route;
    }
}