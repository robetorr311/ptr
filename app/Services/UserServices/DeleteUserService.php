<?php
namespace App\Services\UserServices;


use App\Contracts\FileUploadService\ImageServiceInterface;
use App\Contracts\UserContracts\DeleteUserServiceInterface;
use App\Pet;
use App\User;
use App\VehiclePhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DeleteUserService implements DeleteUserServiceInterface
{
    public function deleteUser(User $user)
    {

        DB::beginTransaction();

        try {
            
            $user->delete();

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'data' => $e->getMessage(),
                'code' => 401
            ];
        }
        DB::commit();

        return [
            'data' => 'User deleted',
            'code' => 200
        ];
    }

}