<?php

declare(strict_types=1);

namespace App\Gateway;

use App\Models\PaymentData;

interface PaymentGatewayInterface
{
    /**
     * Charge the given payment intent for $amount.
     * Returns a transaction ID on success.
     *
     * @throws GatewayTimeoutException
     * @throws GatewayException
     */
    public function charge(PaymentData $payment, float $amount): string;
}

/**
 * Fake in-memory gateway — useful for tests and local runs.
 *
 * Behaviour is controlled by static scenario flags:
 *   FakePaymentGateway::$scenario = 'timeout'   → throws GatewayTimeoutException
 *   FakePaymentGateway::$scenario = 'error'      → throws GatewayException
 *   FakePaymentGateway::$scenario = 'declined'   → returns a declined marker
 *                                                   (caller translates to PaymentException::declined)
 *   FakePaymentGateway::$scenario = 'success'    → returns a transaction ID (default)
 */
class FakePaymentGateway implements PaymentGatewayInterface
{
    public static string $scenario = 'success';

    public function charge(PaymentData $payment, float $amount): string
    {
        return match (self::$scenario) {
            'timeout'  => throw new GatewayTimeoutException("Gateway timed out"),
            'error'    => throw new GatewayException("Gateway internal error"),
            'declined' => throw new GatewayException("Card declined: insufficient funds"),
            default    => 'TXN-' . strtoupper(bin2hex(random_bytes(6))),
        };
    }
}
