<?php

namespace App\Listeners\Bid;

use App\Bid;
use App\Events\Bid\BidAccepted;
use App\Notifications\BidAcceptedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BidAcceptedListener
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
     * @param  BidAccepted  $event
     * @return void
     */
    public function handle(BidAccepted $event)
    {
        /* @var Bid $bid */
        $bid = $event->bid;

        /** @var User $user */
        $user = $bid->user;

        $user->notify(new BidAcceptedNotification($bid));
    }
}
