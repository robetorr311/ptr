<?php
namespace App\Contracts\UserContracts;

use App\Contracts\FileUploadService\ImageServiceInterface;
use Illuminate\Http\UploadedFile;

interface CreateUserContract
{
    public function registerDriver($data, $vehiclePhotos, UploadedFile $avatar, UploadedFile $licence, ImageServiceInterface $imageService);

    public function registerOwner($data, ImageServiceInterface $imageService);
}