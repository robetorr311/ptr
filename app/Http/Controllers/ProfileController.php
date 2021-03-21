<?php

namespace App\Http\Controllers;

use App\Contracts\FileUploadService\ImageServiceInterface;
use App\Contracts\UserContracts\GetUserServiceInterface;
use App\Contracts\UserContracts\UpdateUserServiceInterface;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(GetUserServiceInterface $service)
    {
        $user = $service->getLoggedUserProfileData();

        $addPetEndpoint = route('owner.add.pet');


        return view('pages.profile.index', [
            'user' => $user,
            'addPetEndpoint' => $addPetEndpoint
        ]);
    }

    public function update(Request $request, UpdateUserServiceInterface $service, ImageServiceInterface $imageService)
    {
        /** @var User $user */
        $user = auth()->user();
        $response = $service->updateUser($request->all(), $user, $imageService);

        return response()->json($response['data'], $response['code']);
    }

    public function readNotification($id)
    {
        /** @var User $user */
        $user = auth()->user();

        foreach ($user->unreadNotifications as $notification) {
            if ($notification->id == $id) {
                $notification->markAsRead();
            }
        }

        return response()->json('Success');
    }
}
