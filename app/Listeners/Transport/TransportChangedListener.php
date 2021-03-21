<?php

namespace App\Listeners\Transport;

use App\Transport;
use App\Events\Transport\TransportChanged;
use App\Notifications\TransportChangedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransportChangedListener
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
     * @param  TransportChaned  $event
     * @return void
     */
    public function handle(TransportChanged $event)
    {
        /* @var Transport $transport */
        $transport = $event->transport;

        /** @var User $user */

        $bids = $transport->bids;

        foreach ($bids as $bid) 
        {
            $bid->user->notify(new TransportChangedNotification($transport));
        }

    }
}
