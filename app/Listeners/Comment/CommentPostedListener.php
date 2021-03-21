<?php

namespace App\Listeners\Comment;

use App\Comment;
use App\Events\Comment\CommentPosted;
use App\Notifications\CommentPostedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentPostedListener
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
     * @param  CommentPosted  $event
     * @return void
     */
    public function handle(CommentPosted $event)
    {
        $transport = $event->transport;
        /** @var User $sender */
        $sender = $event->user;


        /** @var Comment $comment */
        foreach ($transport->comments as $comment) {
            if ($comment->user_id != $transport->user_id) {
                if ($sender->id != $comment->user_id) {
                    /** @var User $user */
                    $user = $comment->user;
                    $user->notify(new CommentPostedNotification($transport, $sender));
                }
            }
        }

        if ($transport->user_id != $sender->id) {
            /** @var User $user */
            $user = $transport->owner;

            $user->notify(new CommentPostedNotification($transport, $sender));
        }
    }
}
