<?php
namespace App\Services\UserServices;

use App\Contracts\FileUploadService\ImageServiceInterface;
use App\Contracts\UserContracts\CreateUserContract;
use App\DriverDetails;
use App\User;
use App\VehiclePhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateUserService implements CreateUserContract
{
    public function registerDriver($data, $vehiclePhotos, UploadedFile $avatar, UploadedFile $licence, ImageServiceInterface $imageService)
    {
        DB::beginTransaction();

        try {

            /** @var User $user */
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_token' => str_random(30),
                'phone' => $data['phone']
            ]);

            $licenceName = $user->id . '_' . 'licence.' . $avatar->getClientOriginalExtension();

            if ($licenceUrl = $imageService->storeLicence($licence, $licenceName)) {
                $driverDetails = DriverDetails::create([
                    'user_id' => $user->id,
                    'vehicle_type' => $data['vehicle'],
                    'drivers_licence' => $licenceUrl,
                ]);
            }

            /** @var UploadedFile $vehicleImage */
            foreach ($vehiclePhotos as $key => $vehicleImage) {
                $name = $user->id .'_' .'vehicle_'. $key . '.' .$vehicleImage->getClientOriginalExtension();

                if ($photoUrl = $imageService->storeVehicle($vehicleImage, $name)) {
                    $vehiclePhoto = VehiclePhoto::create([
                        'user_id' => $user->id,
                        'photo' => $photoUrl
                    ]);
                }
            }

            $avatarName = $user->id .'_' .'avatar.' .$avatar->getClientOriginalExtension();

            if ($avatarUrl = $imageService->storeAvatar($avatar, $avatarName)) {
                $user->avatar = $avatarUrl;
                $user->save();
            }

            $user->assignRole('driver');

            if (App::environment('local')) {
                $user->verified = 1;
                $user->save();
            }

        } catch (\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }

        DB::commit();

        return $user;
    }


    public function registerOwner($data, ImageServiceInterface $imageService)
    {
        DB::beginTransaction();

        try {
            /* @var \App\User $user */
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_token' => str_random(30)
            ]);

            if (App::environment('local')) {
                $user->verified = 1;
                $user->save();
            }

            /* @var UploadedFile $avatar */
            $avatar = $data['avatar'];

            LOG::error($data);

            $avatarName = $user->id .'_' .'avatar.' .$avatar->getClientOriginalExtension();

            if ($avatarUrl = $imageService->storeAvatar($avatar, $avatarName)) {
                $user->avatar = $avatarUrl;
                $user->save();
            }

            $user->assignRole('owner');
        } catch (\Exception $e) {
            Log::error('Owner Registration');
            Log::error($e->getMessage());

            DB::rollBack();
            return false;
        }

        DB::commit();

        return $user;
    }
}