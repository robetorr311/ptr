<?php

namespace App\Notifications;

use App\Transport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TransportFinishedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Transport
     */
    public $transport;
    public $sender;

    /**
     * Create a new notification instance.
     *
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        //
        $this->transport = $transport;

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
                    ->line($this->sender->name . ' has set transport to finished. You can ask the Pet owner for the cashout code.')
                    ->action('Go To Cashout Page', url('/'))
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
            'type' => 'transport_finished',
            'transport_id' => $this->transport->id,
            'sender' => $this->sender,
            'link' => route('driver.transportDetails', ['transport' => $this->transport->id]),
            'text' => $this->sender->name . ' has set transport to finished. You can ask the Pet owner for the cashout code.'
        ];
    }
}
