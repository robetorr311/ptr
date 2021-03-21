<?php
namespace App\Contracts\UserContracts;

use App\Contracts\FileUploadService\ImageServiceInterface;
use App\User;

interface UpdateUserServiceInterface
{
    public function verifyUser(User $user);

    public function addPet(User $user, $data);

    public function updateUser($data, User $user, ImageServiceInterface $imageService);
}