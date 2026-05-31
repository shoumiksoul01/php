<?php

namespace App\PaymentGateway\Stripe;

class Transaction
{
    public function charge(float $amount): string
    {
        return "Stripe charged: $$amount";
    }
}