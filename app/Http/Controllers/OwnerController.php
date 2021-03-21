<?php

namespace App\Http\Controllers;

use App\Bid;
use App\Contracts\PaymentContracts\PaymentInterface;
use App\Contracts\TransportContracts\TransportServiceInterface;
use App\Contracts\UserContracts\GetUserServiceInterface;
use App\Contracts\UserContracts\UpdateUserServiceInterface;
use App\Events\Bid\BidAccepted;
use App\Events\Bid\BidDeclined;
use App\Events\Transport\TransportFinished;
use App\Events\Transport\TransportChanged;
use App\Http\Requests\ApiPostPetRequest;
use App\Http\Requests\FinishTransportRequest;
use App\Http\Requests\PostTransportRequest;
use App\Services\BidServices\BidService;
use App\Transport;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerController extends Controller
{

    /**
     * Logged in user
     * @var User $user
     */
    public $user;

    /**
     * OwnerController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();

            return $next($request);
        });
    }

    public function index(GetUserServiceInterface $userService)
    {
        $addPetEndpoint = route('owner.add.pet');

        $transportEndpoint = route('owner.post.transport');
        $myTransportsEndpoint = route('profile.index');

        return view('pages.owner.index', [
            'addPetEndpoint' => $addPetEndpoint,
            'pets' => json_encode($this->user->pets),
            'transportEndpoint' => $transportEndpoint,
            'myTransportsEndpoint' => $myTransportsEndpoint
        ]);
    }
    public function createTransport(GetUserServiceInterface $userService)
    {
        $addPetEndpoint = route('owner.add.pet');

        $transportEndpoint = route('owner.post.transport');
        $myTransportsEndpoint = route('profile.index');

        return view('pages.owner.transport-create', [
            'addPetEndpoint' => $addPetEndpoint,
            'pets' => json_encode($this->user->pets),
            'transportEndpoint' => $transportEndpoint,
            'myTransportsEndpoint' => $myTransportsEndpoint
        ]);
    }

    public function update(Request $request) 
    {
        $id = $request->route('id');

        $transport = \App\Transport::findOrFail($id);


        // dd($transport);

        $transportEndpoint = route('owner.put.transport', ['id' => $id]);

        return view('pages.owner.edit', ['transport' => $transport, 'transportEndpoint' => $transportEndpoint]);
    }

    public function addPet(ApiPostPetRequest $request, UpdateUserServiceInterface $updateUserService)
    {
        return new JsonResponse($updateUserService->addPet($this->user, $request->all()));
    }

    public function transport(PostTransportRequest $request, TransportServiceInterface $service)
    {

        $data = $request->all();

        $data['user_id'] = $this->user->id;

        $transport = $service->createTransport($data);

        return response()->json($transport);
    }

    public function transportDetails(Request $request, Transport $transport)
    {
        $transport->bids;
        $transport->load(['comments' => function($q){
            $q->orderBy('created_at', 'desc');
        }]);

        $stripe_pk_key = env('STRIPE_PUBLIC_KEY');


        return view('pages.owner.transport-details', [
            'transport' => $transport,
            'stripe_pk_key' => $stripe_pk_key,
        ]);
    }

    public function acceptBid(Request $request, Bid $bid, PaymentInterface $paymentService)
    {
        $bidService = new BidService();

        try {
            $bid = $bidService->acceptBid($request->all(), $bid, $paymentService);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        event(new BidAccepted($bid));

        return new JsonResponse('Bid accepted');
    }



    public function deleteBid(Request $request) 
    {
        $bidService = new BidService();

        $bidForDeletion = \App\Bid::findOrFail($request->route('id'));

        try {
            $bid = $bidService->deleteBid($bidForDeletion);
        } catch(\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        event(new BidDeclined($bid));

        return new JsonResponse('Bid declined');
    }

    /** Save edited transport */
    public function updateTransport(Request $request, TransportServiceInterface $service) {
    
        $data = $request->all();

        $data['user_id'] = $this->user->id;
        
        $id = $request->route('id');

        $transport = $service->updateTransport($data, $id);

        event(new TransportChanged($transport));

        return response()->json($transport);
        
    }

    public function finishTransport(FinishTransportRequest $request, Bid $bid, TransportServiceInterface $service)
    {
        try {
            $bid = $service->finishTransport($request->all(), $bid);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        event(new TransportFinished($bid));

        return response()->json($bid->toArray());
    }

    public function showDriverData(Request $request, Bid $bid, TransportServiceInterface $service) 
    {
        try {
            $driver = $bid->user;
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json(['driver' => $driver]);
    }

    public function showPayoutCode(Request $request, Transport $transport, TransportServiceInterface $service)
    {
        try {
            $payCode = $service->getPaycode($transport);
        } catch (\Exception $e) {
            return response()->json('Payment code not found', 404);
        }

        return response()->json(['code' => $payCode]);
    }

    public function showDriverProfile(User $driver, GetUserServiceInterface $userService)
    {
        $driver = $userService->getDriverProfileData($driver);

        return view('pages.owner.driver-profile', compact('driver'));
    }
}
