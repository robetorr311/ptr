<?php

namespace App\Notifications;

use App\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BidDeclinedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Bid
     */
    public $bid;
    public $sender;

    /**
     * Create a new notification instance.
     *
     * @param Bid $bid
     */
    public function __construct(Bid $bid)
    {
        //
        $this->bid = $bid;

        $this->sender = auth()->user();
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
                    ->line($this->sender->name . ' has declined your bid.')
                    ->action('Go To Transport Page to bid again', url('/'))
                    ->line('Thank you for using Pet Travel Hub!');
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
            'type' => 'bid_declined',
            'bid_id' => $this->bid->id,
            'sender' => $this->sender,
            'link' => route('driver.transportDetails', ['transport' => $this->bid->transport_id]),
            'text' => $this->sender->name . ' has declined your bid.'
        ];
    }
}
