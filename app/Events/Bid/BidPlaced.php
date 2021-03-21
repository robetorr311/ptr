<?php

namespace App\Events\Bid;

use App\Bid;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BidPlaced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /* @var Bid $bid */
    public $bid;
    /* @var User $user */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Bid $bid
     * @param User $user
     */
    public function __construct(Bid $bid, User $user)
    {
        $this->bid = $bid;
        $this->user = $user;
    }
}
