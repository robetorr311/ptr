<?php

namespace App\Listeners\Transport;

use App\Transport;
use App\Events\Transport\TransportFinished;
use App\Notifications\TransportFinishedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransportFinishedListener
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
     * @param  TransportFinished  $event
     * @return void
     */
    public function handle(TransportFinished $event)
    {
        /* @var Transport $transport */
        $bid = $event->bid;
        $transport = $bid->transport;

        $bid->user->notify(new TransportFinishedNotification($transport));
    }
}
