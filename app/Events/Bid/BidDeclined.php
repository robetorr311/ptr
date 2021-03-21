<?php

namespace App\Events\Bid;

use App\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BidDeclined
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Bid
     */
    public $bid;

    /**
     * Create a new event instance.
     *
     * @param Bid $bid
     */
    public function __construct(Bid $bid)
    {
        //
        $this->bid = $bid;
    }

}
