<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Immutable value object carrying payment intent data.
 */
class PaymentData
{
    public function __construct(
        private readonly string $intentId,
        private readonly string $cardToken,
        private readonly string $currency = 'USD'
    ) {}

    public function getIntentId(): string  { return $this->intentId; }
    public function getCardToken(): string { return $this->cardToken; }
    public function getCurrency(): string  { return $this->currency; }
}
