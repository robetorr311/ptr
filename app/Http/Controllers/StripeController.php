<?php

namespace App\Http\Controllers;

use App\Services\PaymentServices\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Payout;
use App\User;
use App\Payment;
use App\Bid;
use App\Transport;
use Stripe\Transfer;

class StripeController extends Controller
{
    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_CASHED = 'cashed';
    const STATUS_AWAITING = 'awaiting';
    public function showConnect(Request $request)
    {
        $stripe_client_id = env('STRIPE_CLIENT_ID');
        $url = 'https://connect.stripe.com/express/oauth/authorize?response_type=code&client_id=&scope=read_write&redirect_uri='.route('stripe.connect.post');
 
        return redirect($url);
    }
    public function verifyConnect(Request $request)
    {
        $data = $request->all();
        $code = $data['code'];
        $token_request_body = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_secret' => ''
        );
        $tokenUri="https://connect.stripe.com/oauth/token";
        $request = curl_init($tokenUri);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, $token_request_body);
        $response = json_decode(curl_exec($request), true);
        curl_close($request);
        $stripe_id = $response['stripe_user_id'];

        $user = auth()->user();

        $user->stripe_id = $stripe_id;
        $user->save();

        return redirect()->route('driver.show.cashout');
    }
    public function Getpayment($bid,Request $request)
    {
        $data = $request->all();
        $bids=DB::table('bids')->where('id','=',$bid)->first();
            $amount=$bids->amount;
            $user_id=$bids->user_id;
            $transport_id = $bids->transport_id;
        $transports=DB::table('transports')->where('id','=',$transport_id)->first();
        $driver_id=$transports->user_id;
        $drivers=DB::table('users')->where('id','=',$driver_id)->first();
        $stripe_driver_id=$drivers->stripe_id;            
        Stripe::setApiKey('');
        $token=$data['id'];
        $twenty=round((12.9*$amount)/100);
        $transport_amount=$amount-$twenty;        
        $charge = Charge::create([
                            "amount" => 100*(int)$amount,
                            "currency" => "usd",
                            "source" => $token,
                            "metadata" => ["order_id" => $bid]
                            ]);
        $table_transport = Transport::findOrFail($transport_id);
        $table_bid= Bid::findOrFail($bid);
        $user = auth()->user();
        $table_bid->status = self::STATUS_ACCEPTED;
        $table_bid->save();

        /** @var Transport $transport */
        $table_transport->status = Transport::STATUS_PROGRESS;
        $table_transport->save();
        $payment=Payment::create([
                'user_id' => $user->id,
                'bid_id' => $bid,
                'amount' => (int)$transport_amount,
                'type' => 'payin',
                'transaction_id' => $charge->id
        ]);
        $payment->save();       
        return response()->json($charge);
    }  
    public function payout($bid,Request $request)
    {
        $data = $request->all();
        $bids=DB::table('payments')->where('bid_id','=',$bid)->first();
            $amount=$bids->amount;
        $user = auth()->user();
        $stripe_id=$user->stripe_id;            
        Stripe::setApiKey('');
        $twenty=round((12.9*$amount)/100);
        $transport_amount=$amount-$twenty;        
        $transactionObject = Transfer::create([
                'amount' => 100 * (int)$transport_amount,
                'currency' => 'usd',
                'destination' => $stripe_id,
        ]);
        $table_bid= Bid::findOrFail($bid);
        $table_bid->status = self::STATUS_CASHED;
        $table_bid->save();
        $table_transport = Transport::where('id', $table_bid->transport_id)->first();
        /** @var Transport $transport */
        $table_transport->status = Transport::STATUS_CASHED;
        $table_transport->save();
        $payment=Payment::create([
                'user_id' => $user->id,
                'bid_id' => $bid,
                'amount' => (int)$amount,
                'type' => 'payout',
                'transaction_id' => $charge->id
        ]);
        $payment->save();       
        return response()->json($charge);
    }          
}
