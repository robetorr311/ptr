<?php

namespace App\Events\Transport;

use App\Transport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TransportChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Transport
     */
    public $transport;

    /**
     * Create a new event instance.
     *
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        //
        $this->transport = $transport;
    }

}
