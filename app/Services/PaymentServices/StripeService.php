<?php
namespace App\Services\PaymentServices;

use App\Contracts\PaymentContracts\PaymentInterface;
use App\User;
use Psy\Util\Str;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Payout;
use Stripe\Stripe;
use Stripe\Transfer;
use Illuminate\Http\Request;

class StripeService implements PaymentInterface
{

    public $stripe_api_key;
    /**
     * StripeService constructor.
     */
    public function __construct()
    {
        $this->stripe_api_key = 'pk_live_Na776R7Bk6mDkdIWFgiKQlUv';
    }

    /**
     * @param $data
     * @param User $user
     * @return \Stripe\ApiResource
     * @throws \Exception
     */
    public function deposit($data, User $user, Request $request)
    {
        Stripe::setApiKey('pk_live_Na776R7Bk6mDkdIWFgiKQlUv');
        $token=$request->stripeToken;
        //$token='tok_mastercard_debit_transferSuccess';
        $stripe_id_transport=$user->stripe_id;
        $pettravel_id='ca_Gw16JwAHdqceZB15KyFzqmCF0U5XH0Em';
        $totalamount=(int)$data['amount'];
        $twenty=round((20/$totalamount)*100);
        $pettravelamount=round((10/$totalamount)*100);
        $transport_amount=$totalamount-$twenty;
        $charge = Charge::create([
                            "amount" => (int)$data['amount'],
                            "currency" => "usd",
                            "source" => $token,
                            "metadata" => ["order_id" => "6735"]
                            ]);

        return $charge;
    }

    /**
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public function verifyConnect($code)
    {
        //$client_secret='ca_Gw16JwAHdqceZB15KyFzqmCF0U5XH0Em';
        $token_request_body = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_secret' => 'sk_test_GQfC8ZVFv7A6bADv8sojSCeQ'
        );
        $tokenUri="https://connect.stripe.com/oauth/token";
        $request = curl_init($tokenUri);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, $token_request_body);
        $response = json_decode(curl_exec($request), true);
        curl_close($request);
        if (isset($response['error'])) {

            throw new \Exception($response['error_description']);

        }


        return $response['stripe_user_id'];
    }

    /**
     * @param $stripe_id
     * @param $amount
     * @return mixed|null
     * @throws \Exception
     */
    public function payout($stripe_id, $amount)
    {
        Stripe::setApiKey($this->stripe_api_key);

        try{
            $transactionObject = Transfer::create([
                'amount' => 100 * (int)$amount,
                'currency' => 'usd',
                'destination' => $stripe_id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        return $transactionObject->id;
    }


}