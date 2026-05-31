<?php

namespace App\PaymentGateway\Paddle;

class Transaction
{
    public function charge(float $amount): string
    {
        return "Paddle charged: $$amount";
    }
}