<?php

namespace App\Contracts;

interface SmsService {
    public function send(string $to, string $message);
}
