<?php

namespace App\Listeners\Bid;

use App\Events\Bid\BidPlaced;
use App\Notifications\BidPlacedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BidPlacedListener
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
     * @param  BidPlaced  $event
     * @return void
     */
    public function handle(BidPlaced $event)
    {
        $event->user->notify(new BidPlacedNotification($event->bid));
    }
}
