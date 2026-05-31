<?php

namespace App\PaymentGateway\Paddle;

class CustomerProfile
{
    public function __construct(private string $name) {}

    public function getName(): string
    {
        return $this->name;
    }
}