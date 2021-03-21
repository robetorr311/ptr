<?php

namespace App\Http\Controllers;

use App\Bid;
use App\Contracts\PaymentContracts\PaymentInterface;
use App\Contracts\TransportContracts\TransportServiceInterface;
use App\Events\Bid\BidPlaced;
use App\Http\Requests\BidRequest;
use App\Http\Requests\CashoutRequest;
use App\Payment;
use App\Services\BidServices\BidService;
use App\Transport;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('pages.driver.index');
    }

    public function baseSearch(Request $request, TransportServiceInterface $service)
    {
        if ($request->ajax()) {
            $data = $request->all();

            $transports = $service->getTransports($data);

            return json_encode($transports);
        }

        $transports = $service->getTransports();
        $dataEndpoint = route('driver.search.base');
        $geoEndpoint = route('driver.search.geo');

        return view('pages.driver.search-base',
            [
                'transports' => $transports,
                'dataEndpoint' => $dataEndpoint,
                'geoEndpoint' => $geoEndpoint
            ]);
    }

    public function geoSearch(Request $request, TransportServiceInterface $service)
    {
        if ($request->ajax()) {

//            $this->validate($request, [
//                'lat' => 'required',
//                'lng' => 'required',
//                'radius' => 'required'
//            ]);

            $transports = $service->getTransportsByRadius($request->all());


            return json_encode($transports);

        }

        $transports = $service->getTransportsByRadius([]);

        $geoEndpoint = route('driver.search.geo');
        return view('pages.driver.search-geo', [
            'geoEndpoint' => $geoEndpoint,
            'allTransports' => $transports
        ]);
    }

    public function bid(BidRequest $request, Transport $transport)
    {
        $service = new BidService();
        $data = $request->all();

        /** @var User $user */
        $user = auth()->user();
        $data['user_id'] = $user->id;

        try {
            $bid = $service->placeBid($data, $transport);

        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 401);
        }

        event(new BidPlaced($bid, $transport->owner));

        return new JsonResponse('Bid placed');
    }
    public function showCashout(Request $request)
    {
        $bidService = new BidService();
        /** @var User $user */
        $user = auth()->user();

        $bids = $bidService->getAcceptedBids($user);


        return view('pages.driver.cashout', compact(['bids', 'user']));
    }

    public function cashout(CashoutRequest $request, Bid $bid, PaymentInterface $stripe)
    {

        $response = (new BidService())->cashoutProcess($bid, $request->get('code'), $stripe);

        return response()->json($response['data'], $response['code']);
    }

    public function transportDetails(Transport $transport)
    {
        $transport->bids;
        if ($transport->status != Transport::STATUS_OPEN) {
            $check = false;
            /** @var Bid $bid */
            foreach ($transport->bids as $bid) {
                if ($bid->user_id == auth()->user()->id &&
                    ($bid->status == BidService::STATUS_ACCEPTED)
                        || ($bid->status == BidService::STATUS_CASHED
                        || ($bid->status == BidService::STATUS_COMPLETED)
                        || ($bid->status == BidService::STATUS_AWAITING)
                        )
                    ){
                    $check = true;
                }
            }
            if (!$check)
                return redirect()->url('/');
        }
        $transport->owner;
        $transport->load(['comments' => function($q){
            $q->orderBy('created_at', 'desc');
        }]);

        return view('pages.driver.transportDetails', compact('transport'));
    }

    public function transportOwnerDetails(Transport $transport) {
        $owner = $transport->owner;

        return response()->json(['owner' => $owner]);
    }
}
