<?php

namespace App\Events\Transport;

use App\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TransportFinished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Transport
     */
    public $bid;

    /**
     * Create a new event instance.
     *
     * @param Transport $transport
     */
    public function __construct(Bid $bid)
    {
        //
        $this->bid = $bid;
    }

}
