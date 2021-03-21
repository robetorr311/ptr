<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Mail\ContactUsMail;
use App\Transport;
use App\Mail\VerificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Contracts\FileUploadService\ImageServiceInterface;
use App\Contracts\UserContracts\CreateUserContract;
use App\DriverDetails;
use App\Http\Requests\RegisterDriverRequest;
use App\Http\Requests\RegisterOwnerRequest;
use App\User;
use App\VehiclePhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ownerRegistrationEndpoint = route('register.owner');
        $loginEndpoint = '/login';
        return view('landing', [
            'ownerRegistrationEndpoint' => $ownerRegistrationEndpoint,
            'loginEndpoint' => $loginEndpoint,
            'driverEndpoint' => route('register.driver')
        ]);
    }

    public function ownerView()
    {
        $ownerRegistrationEndpoint = route('register.owner');
        $loginEndpoint = '/login';
        return view('auth.register-owner', [
            'ownerRegistrationEndpoint' => $ownerRegistrationEndpoint,
            'loginEndpoint' => $loginEndpoint
        ]);
    }

    public function driverView()
    {
        $driverEndpoint = route('register.driver');
        $loginEndpoint = '/login';
        return view('auth.register-driver', [
            'driverEndpoint' => $driverEndpoint,
            'loginEndpoint' => $loginEndpoint
        ]);
    }

    public function test()
    {
        dd(route('verify.email', ['token' => 'asdas']));

    }

    function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {

        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d;

    }
    public function register()
    {
        return redirect('register/owner');
        // $ownerRegistrationEndpoint = route('register.owner');
        // $loginEndpoint = '/login';
        // return view('auth.register', [
        //     'ownerRegistrationEndpoint' => $ownerRegistrationEndpoint,
        //     'loginEndpoint' => $loginEndpoint,
        //     'driverEndpoint' => route('register.driver')
        // ]);
    }
    public function registernewowner(RegisterOwnerRequest $request,  ImageServiceInterface $imageService, CreateUserContract $userService)
    {
        $data = $request->all();

        if(!$user = $userService->registerOwner($data, $imageService)){
            return redirect()->route('login')->with('error', 'Error! User could not be registered');
        }

        $data=$this->sendEmail($user->id);

        return redirect()->route('login')->with('message', 'Thank you. In order to complete the registration, please click on the verification link sent to '. $user->email);
    }
    public function registernewdriver(RegisterDriverRequest $request, ImageServiceInterface $imageService, CreateUserContract $userService)
    {
        $data = $request->all();

        $vehiclePhotos = $request->file('vehicle_photos');
        $avatar = $request->file('avatar');
        $licence = $request->file('drivers_licence');

        $user = $userService->registerDriver($data, $vehiclePhotos, $avatar, $licence, $imageService);

        if(!$user)
            return back()->with('error', 'User registration failed. Please try again later.');

        //Mail::to($user)->queue(new VerificationEmail($user));
        $data=$this->sendEmail($user->id);
        return redirect()->route('login')->with('message', 'Thank you. In order to complete the registration, please click on the verification link sent to '. $user->email);
    }
    public function sendEmail($id){
        try {
            $user=DB::table('users')->where('id','=',$id)->first();
            $name=$user->name;
            $email=$user->email;
            $token = $user->email_token;
            $linkaddress=route('verify.email',['token' => $token]);
            $data= array('name' => $name, 'email' => $email, 'link'=> $linkaddress );          
              Mail::send('emails.verification_mail',$data,function($message) use ($data){
                  $message->to($data['email'],$data['name'])->subject('test');
            });
        }
        catch (Exception $e){}
        return $data;
    }
    public function verifyemail()
    {
        return redirect()->route('login')->with('message', 'Email successfully verified. You can now login');
    }    
    public function landingInfo()
    {
        return view('pages.landing-info');
    }

    public function aboutUs()
    {
        return view('pages.about-us');
    }

    public function contactUs()
    {
        return view('pages.contact-us');
    }

    public function contactUsSubmit(ContactUsRequest $request)
    {
        Mail::to(env('CONTACT_US_EMAIL', 'pets@pettravelhub.com'))->send(new ContactUsMail($request->all()));

        return redirect()->route('thank-you');
    }

    public function thankYou()
    {
        return view('pages.thank-you');
    }

    public function faq()
    {
        return view('pages.faq');
    }
}
