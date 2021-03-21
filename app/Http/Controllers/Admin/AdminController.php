<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $users = \App\User::get();
        $transportations = \App\Transport::get();

        return view('admin.dashboard', ['users' => $users, 'transportations' => json_encode($transportations)]);
    }

    public function getAllOwners(Request $request) 
    {

        $owners = \App\User::whereHas('roles', function ($query) {
            $query->where('name', 'owner');
        })->get();


        return view('admin.owners-list', ['owners' => $owners]);
    }

    public function getAllDrivers(Request $request) 
    {
        $drivers = \App\User::whereHas('roles', function($query) {
            $query->where('name', 'driver');
        })->get();

        foreach ($drivers as $driver) 
        {
            $photos = $driver->vehiclePhotos;
        }


        return view('admin.drivers-list', ['drivers' => $drivers]);
    }

    public function getAllTransportations(Request $request) {
        $transportations = \App\Transport::get();

        return view('admin.transport-list', ['transportations' => $transportations]);
    }

    public function deleteTransport(Request $request) {

        $transport = \App\Transport::findOrFail($request->route('id'));

        $transport->delete();

        return redirect()->route('admin.index')
        ->with('success','Transport was deleted');

    }

    public function deleteUser(Request $request) {
        $user = \App\User::findOrFail($request->route('id'));

        $userService = new \App\Services\UserServices\DeleteUserService();

        $userService->deleteUser($user);

        return redirect()->route('admin.index')
        ->with('success', 'User has been deleted');
    }
}
