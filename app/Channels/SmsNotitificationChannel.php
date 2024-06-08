<?php

namespace App\Channels;

use App\Contracts\SmsService;
use Illuminate\Notifications\Notification;

class SmsNotitificationChannel {

    public function __construct(protected SmsService $smsService) {

    }

    public function send($notifiable, Notification $notification) {
        $data = $notification->toSmsChannel($notifiable);

        $this->smsService->send($notifiable->email, 'Your MFA code is: '. $data['code']);
    }

}
