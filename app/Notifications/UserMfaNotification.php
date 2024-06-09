<?php

namespace App\Notifications;

use App\Channels\SmsNotitificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMfaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;
    protected $provider;
    /**
     * Create a new notification instance.
     */
    public function __construct($code, $provider = 'mail')
    {
        $this->code = $code;

        match ($provider) {
            'phone' => $this->provider = SmsNotitificationChannel::class,
            'mail' => $this->provider = 'mail',
        };
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [$this->provider];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Your authorization code')
                    ->line('Your Login Code: '. $this->code)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toSmsChannel(object $notifiable) {
        return [
            'code' => $this->code
        ];
    }
}
