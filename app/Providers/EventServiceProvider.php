<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Bid\BidPlaced' => [
            'App\Listeners\Bid\BidPlacedListener',
        ],
        'App\Events\Bid\BidAccepted' => [
            'App\Listeners\Bid\BidAcceptedListener',
        ],
        'App\Events\Bid\BidDeclined' => [
            'App\Listeners\Bid\BidDeclinedListener',
        ],
        'App\Events\Comment\CommentPosted' => [
            'App\Listeners\Comment\CommentPostedListener',
        ],

        'App\Events\Transport\TransportFinished' => [
            'App\Listeners\Transport\TransportFinishedListener',
        ],

        'App\Events\Transport\TransportChanged' => [
            'App\Listeners\Transport\TransportChangedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
