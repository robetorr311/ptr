<?php
namespace App\Contracts\UserContracts;

use App\User;

interface DeleteUserServiceInterface
{
    public function deleteUser(User $user);

}