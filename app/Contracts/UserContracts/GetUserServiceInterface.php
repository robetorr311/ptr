<?php
namespace App\Contracts\UserContracts;

use App\User;

interface GetUserServiceInterface
{
    public function getUserWithEmailToken($token);

    public function getLoggedUserProfileData();

    public function getDriverProfileData(User $user);
}