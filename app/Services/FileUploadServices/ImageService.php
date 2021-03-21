<?php
namespace App\Services\FileUploadServices;

use App\Contracts\FileUploadService\ImageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class ImageService implements ImageServiceInterface
{
    public function storeAvatar(UploadedFile $image, $name)
    {
        $directory = config('uploads.avatar_directory');

        return $this->storeImage($image, $directory, $name, 1024, null);
    }

    public function storeLicence(UploadedFile $image, $name)
    {
        $directory = config('uploads.licence_directory');

        return $this->storeImage($image, $directory, $name, 1024, null);
    }

    public function storeVehicle(UploadedFile $image, $name)
    {
        $directory = config('uploads.vehicle_directory');

        return $this->storeImage($image, $directory, $name, 1024, null);
    }

    protected function storeImage(UploadedFile $image, $directory, $name, $width = null, $height = null)
    {
        try {

            $img = Image::make($image);

            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $saveDirectory = public_path().$directory;

            File::exists($saveDirectory) or File::makeDirectory($saveDirectory, 0775, true, false);

            $img->save($saveDirectory . $name, 60);

            return $directory . $name;

        } catch (\PostTooLargeException $e) {
            Log::error('Image upload error:');
            Log::error($e->getMessage());
            return response()->json('Error while trying to upload the photo');
        }

        return false;
    }
    
}