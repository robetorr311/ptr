<?php
namespace App\Services\BidServices;

use App\Bid;
use App\Contracts\PaymentContracts\PaymentInterface;
use App\Payment;
use App\Transport;
use App\User;
use Illuminate\Support\Facades\DB;

use App\Events\Bid\BidDeclined;

class BidService
{
    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_CASHED = 'cashed';
    const STATUS_AWAITING = 'awaiting';

    /**
     * @param $data
     * @param Transport $transport
     * @return
     * @throws \Exception
     */
    public function placeBid($data, Transport $transport)
    {
        $this->validateBid($transport);

        if ($transport->first_bid_buy == 1 && !empty($transport->bids)) {
            $transport->status = Transport::STATUS_AWAITING;
            $transport->biddable = 0;
            $transport->save();
            $data['transport_id'] = $transport->id;
            $data['status'] = self::STATUS_AWAITING;
        } else {
            $data['transport_id'] = $transport->id;
            $data['status'] = self::STATUS_OPEN;
        }

        return Bid::create($data);
    }

    // public function placeBuy($data, Transport $transport)
    // {
    //     $this->validateBid($transport);

    //     $data['transport_id'] = $transport->id;
    //     $data['status'] = self::STATUS_AWAITING;

    //     return Bid::create($data);
    // }

    /**
     * @param Transport $transport
     * @throws \Exception
     */
    public function validateBid(Transport $transport)
    {
        if ($transport->status != 'open' || ($transport->biddable != 1 && $transport->first_bid_buy != 1))
            throw new \Exception('Transport is not open for bidding!');
    }

    /**
     * @param $data
     * @param Bid $bid
     * @param PaymentInterface $paymentService
     * @return Bid
     * @throws \Exception
     */
    public function acceptBid($data, Bid $bid, PaymentInterface $paymentService)
    {

            $data['amount'] = $bid->amount;
            /* @var User $user */
            $user = auth()->user();

            $chargeObject = $paymentService->deposit($data, $user);

            $bid->status = self::STATUS_ACCEPTED;
            $bid->save();
            /** @var Transport $transport */
            $transport = $bid->transport;
            $totalamount=(int)$bid->amount;
            $twenty=round((20/$totalamount)*100);
            $pettravelamount=round((10/$totalamount)*100);
            $transport_amount=$totalamount-$twenty;
            $transport->status = Transport::STATUS_PROGRESS;
            $transport->save();

            $payment=Payment::create([
                'user_id' => $user->id,
                'bid_id' => $bid->id,
                'amount' => (int)$transport_amount,
                'type' => 'payin',
                'transaction_id' => $chargeObject->id
            ]);
            $payment->save();
        return $bid;
    }

    public function deleteBid(Bid $bid)
    {
        DB::beginTransaction();

        try {
          
            $bid->delete();


            /** @var Transport $transport */
            $transport = $bid->transport;
            $transport->status = Transport::STATUS_OPEN;
            $transport->save();


        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $bid;
    }

    public function finishBid(Bid $bid, $payout_code)
    {
        $bid->status = self::STATUS_COMPLETED;
        $bid->payout_code = $payout_code;
        return $bid->save();
    }

    public function declineBid(Bid $bid) 
    {
        $bid->delete();

        return $bid;
    }

    public function getAcceptedBids(User $user)
    {
        if ($user)
            return $user->acceptedBids;

        return false;
    }

    public function cashoutProcess(Bid $bid, $code, PaymentInterface $stripe)
    {
        $response = [
            'data' => [],
            'code' => 401
        ];

        if ($bid->status != self::STATUS_COMPLETED) {
            $response['data'] = 'Error! Bid has to be completed in order to be cashed!';
            return $response;
        }

        if (strtolower($bid->payout_code) != strtolower($code)) {
            $response['data'] = 'Invalid Code';
            return $response;
        }

        $stripe_id = $bid->user->stripe_id;

        DB::beginTransaction();
        try {
            $transation_id = $stripe->payout($stripe_id, (int)$bid->amount);

            $payment = Payment::create([
                'user_id' => $bid->user->id,
                'bid_id' => $bid->id,
                'amount' => (int)$bid->amount,
                'type' => 'payout',
                'transaction_id' => $transation_id
            ]);

            $bid->status = self::STATUS_CASHED;

            $bid->transport->status = \App\Transport::STATUS_CASHED;

            $bid->transport->save();
            $bid->save();

        } catch (\Exception $e) {
            DB::rollBack();
            $response['data'] = $e->getMessage();
            return $response;
        }
        DB::commit();

        $response['data'] = 'Cashout successful';
        $response['code'] = 200;

        return $response;
    }
}