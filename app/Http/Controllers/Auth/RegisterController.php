<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\FileUploadService\ImageServiceInterface;
use App\Contracts\UserContracts\CreateUserContract;
use App\DriverDetails;
use App\Http\Requests\RegisterDriverRequest;
use App\Http\Requests\RegisterOwnerRequest;
use App\Mail\VerificationEmail;
use App\User;
use App\Http\Controllers\Controller;
use App\VehiclePhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_token' => str_random(30)
        ]);
    }


    /**
     * @param RegisterOwnerRequest $request
     * @param ImageServiceInterface $imageService
     * @param CreateUserContract $userService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function owner(RegisterOwnerRequest $request,  ImageServiceInterface $imageService, CreateUserContract $userService)
    {
        $data = $request->all();

        if(!$user = $userService->registerOwner($data, $imageService)){
            return redirect()->route('login')->with('error', 'Error! User could not be registered');
        }

        Mail::to($user)->queue(new VerificationEmail($user));

        return redirect()->route('login')->with('message', 'Thank you. In order to complete the registration, please click on the verification link sent to '. $user->email);
    }

    public function driver(RegisterDriverRequest $request, ImageServiceInterface $imageService, CreateUserContract $userService)
    {
        $data = $request->all();

        $vehiclePhotos = $request->file('vehicle_photos');
        $avatar = $request->file('avatar');
        $licence = $request->file('drivers_licence');

        $user = $userService->registerDriver($data, $vehiclePhotos, $avatar, $licence, $imageService);

        if(!$user)
            return back()->with('error', 'User registration failed. Please try again later.');

        Mail::to($user)->queue(new VerificationEmail($user));

        return redirect()->route('login')->with('message', 'Thank you. In order to complete the registration, please click on the verification link sent to '. $user->email);
    }
}
