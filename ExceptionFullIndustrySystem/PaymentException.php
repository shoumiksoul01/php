<?php

declare(strict_types=1);

namespace App\Exceptions;

class PaymentException extends AppException
{
    public function __construct(
        string $message,
        int $code,
        private readonly string $transactionId,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public static function declined(string $txId, string $reason): static
    {
        return new static("Payment $txId declined: $reason", 402, $txId);
    }

    public static function timeout(string $txId): static
    {
        return new static("Payment $txId timed out", 408, $txId);
    }

    public static function gatewayError(string $txId, \Throwable $cause): static
    {
        return new static("Payment gateway error for $txId", 503, $txId, $cause);
    }
}
