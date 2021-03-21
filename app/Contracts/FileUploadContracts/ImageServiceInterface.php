<?php
namespace App\Contracts\FileUploadService;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    public function storeAvatar(UploadedFile $image, $name);

    public function storeVehicle(UploadedFile $image, $name);
}