<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Services\AuthServices\AuthService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();

        return redirect('/');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->all();

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            /* @var User $user */
            $user = auth()->user();

            if ($user->verified == 0) {
                auth()->logout();
                $request->session()->invalidate();
                return back()->with('error', 'Invalid credentials');
            }

            $route = (new AuthService())->resolveRedirectionUrl($user);

            return $route ? redirect($route) : redirect()->route('landing.info')->with('error', 'Whoops! Unknown user role!');
        }

        return back()->with('error', 'Invalid credentials');
    }


}
