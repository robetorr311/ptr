<?php

namespace App\Events\Comment;

use App\Transport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentPosted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transport;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
        $this->user = auth()->user();
    }

}
