<?php

namespace App\Notifications;

use App\Transport;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentPostedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Transport
     */
    public $transport;
    /**
     * @var User
     */
    public $sender;

    public $url;

    /**
     * Create a new notification instance.
     *
     * @param Transport $transport
     * @param User $sender
     */
    public function __construct(Transport $transport, User $sender)
    {
        //
        $this->transport = $transport;
        $this->sender = $sender;

        if ($sender->hasRole('driver')) {
            $this->url = route('owner.transport.details', ['transport' => $transport->id]);
        } else {
            $this->url = route('driver.transportDetails', ['transport' => $transport->id]);
        }
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
                    ->line($this->sender->name . ' posted a comment on a transport')
                    ->action('Go to transport', $this->url)
                    ->line('Thank you for using our application!');
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
            'type' => 'comment',
            'text' => $this->sender->name . ' posted a comment on a transport.',
            'link' => $this->url,
            'sender' => $this->sender
        ];
    }
}
