<?php

namespace App\Notification;

class Email
{
    public function send(string $to): string
    {
        return "Email sent to: $to";
    }
}