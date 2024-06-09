<?php

namespace App\Services;

use App\Contracts\SmsService;

;

class SmsLogService implements SmsService  {
    public function send(string $to, string $message) {
        logger("Sending SMS: ", [$to, $message]);
    }

}
