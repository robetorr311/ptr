<?php

namespace App\Http\Controllers;


use App\Contracts\UserContracts\GetUserServiceInterface;
use App\Contracts\UserContracts\UpdateUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    public function verifyEmail($token, GetUserServiceInterface $getUserService, UpdateUserServiceInterface $updateUserService)
    {

        $user = $getUserService->getUserWithEmailToken($token);

        if (!isset($user))
            return redirect()->route('landing.info')->with('error', 'Email verification failed. User not found.');

        if (!$updateUserService->verifyUser($user))
            return redirect()->route('landing.info')->with('error', 'Email verification failed. User could not be authenticated.');

        return redirect()->route('login')->with('message', 'Email successfully verified. You can now login');
    }
}
