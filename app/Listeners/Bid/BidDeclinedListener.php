<?php

namespace App\Listeners\Bid;

use App\Bid;
use App\Events\Bid\BidDeclined;
use App\Notifications\BidDeclinedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BidDeclinedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BidDeclined  $event
     * @return void
     */
    public function handle(BidDeclined $event)
    {
        /* @var Bid $bid */
        $bid = $event->bid;

        /** @var User $user */
        $user = $bid->user;

        $user->notify(new BidDeclinedNotification($bid));
    }
}
