<?php

namespace App\Notifications;

use App\Bid;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BidPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /* @var Bid $bid */
    public $bid;
    /* @var User $sender */
    public $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
        $this->sender = $bid->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->sender->name . ' has placed a bid on your transport.')
                    ->action('See bid', url('/'))
                    ->line('Thank you for using PetTravelHub!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'bid_placed',
            'bid_id' => $this->bid->id,
            'sender' => $this->sender,
            'link' => route('owner.transport.details', ['transport' => $this->bid->transport_id]),
            'text' => $this->sender->name . ' has placed a bid on your transport.'
        ];
    }
}
