<?php
namespace App\Services\UserServices;

use App\Bid;
use App\Payment;
use App\Contracts\UserContracts\GetUserServiceInterface;
use App\Services\BidServices\BidService;
use App\User;

class GetUserService implements GetUserServiceInterface
{
    /**
     * @param $token
     * @return mixed
     */
    public function getUserWithEmailToken($token)
    {
        return User::where('email_token', $token)->first();
    }

    public function getLoggedUserProfileData()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasRole('owner')) {

            $user->payments;
            $user->pets;
            $user->load('transports.bids');

        } elseif ($user->hasRole('driver')) {

            $user->payments = Payment::query()->with(['user', 'bid.transport.owner', 'bid.user'])->get()->where('user_id', $user->id);
            $user->load('driverDetails');
            $user->load('vehiclePhotos');
            $user->load(['bids' => function ($q) {
                $q->with('transport');
                $q->where('status', BidService::STATUS_CASHED);
                $q->orWhere('status', BidService::STATUS_COMPLETED);
                $q->orWhere('status', BidService::STATUS_ACCEPTED);
            }]);

            $review = $user->reviewOverall();
            $user->reviewOverall = $review;
        }

        //dd($user);

        return $user;
    }

    public function getDriverProfileData(User $user)
    {
        $user->load('driverDetails');
        $user->load(['bids' => function ($q) {
            $q->with('transport');
            $q->where('status', BidService::STATUS_CASHED);
            $q->orWhere('status', BidService::STATUS_COMPLETED);
            $q->orWhere('status', BidService::STATUS_ACCEPTED);
        }]);

        $review = $user->reviewOverall();
        $user = $user->toArray();
        $user['reviewOverall'] = $review;

        return $user;
    }


}