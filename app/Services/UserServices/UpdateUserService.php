<?php
namespace App\Services\UserServices;


use App\Contracts\FileUploadService\ImageServiceInterface;
use App\Contracts\UserContracts\UpdateUserServiceInterface;
use App\Pet;
use App\User;
use App\VehiclePhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdateUserService implements UpdateUserServiceInterface
{
    public function verifyUser(User $user)
    {
        $user->verified = 1;
        $user->email_token = '';
        return $user->save();
    }

    public function addPet(User $user, $data)
    {
        return Pet::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'size' => $data['size'],
            'weight' => $data['weight'],
            'type' => $data['type'],
        ]);
    }

    public function updateUser($data, User $user, ImageServiceInterface $imageService)
    {

        DB::beginTransaction();

        try {
            if (isset($data['old_password'], $data['new_password'])) {
                if (Hash::check($data['old_password'], $user->password)) {
                    $user->password = Hash::make($data['new_password']);
                } else {
                    throw new \Exception('Passwords do not match');
                }
            }
            if (isset($data['avatar'])) {
                /** @var UploadedFile $uploadedFile */
                unlink(public_path($user->avatar));

                $uploadedFile = $data['avatar'];
                $avatar = $imageService->storeAvatar($uploadedFile, $user->id .'_' . time() .'_avatar.' .$uploadedFile->getClientOriginalExtension());
                if ($avatar)
                    $user->avatar = $avatar;
            }

            if ($user->hasRole('driver')) {
                $details = $user->driverDetails;

                if (isset($data['vehicle_type'])) {
                    $details->vehicle_type = $data['vehicle_type'];
                    $details->save();
                }

                if (isset($data['vehicle_photo']) && count($data['vehicle_photo']) > 0) {
                    $existingVehiclePhotos = $user->vehiclePhotos;
                    $this->removeVehiclePhotos($existingVehiclePhotos);
                    

                    /** @var UploadedFile $vehicleImage */
                    foreach ($data['vehicle_photo'] as $key => $vehicleImage) {
                        $name = $user->id .'_' .'vehicle_'. $key . '.' .$vehicleImage->getClientOriginalExtension();

                        if ($photoUrl = $imageService->storeVehicle($vehicleImage, $name)) {
                            $vehiclePhoto = VehiclePhoto::create([
                                'user_id' => $user->id,
                                'photo' => $photoUrl
                            ]);
                        }
                    }

                }

            }

            $user->name = $data['name'];

            if(isset($data['phone'])) {
                $user->phone = $data['phone'];
            }

            $user->save();

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'data' => $e->getMessage(),
                'code' => 401
            ];
        }
        DB::commit();

        return [
            'data' => 'User profile updated',
            'code' => 200
        ];
    }

    
    public function removeVehiclePhotos($vehiclePhotos)
    {
        try {
            /** @var VehiclePhoto $vehiclePhoto */
            foreach ($vehiclePhotos as $vehiclePhoto) {
                unlink(public_path($vehiclePhoto->photo));

                $vehiclePhoto->delete();
            }
        } catch (\Exception $e) {
            Log::error('VehiclePhoto Delete error:');
            Log::error($e->getMessage());
        }
    }
}