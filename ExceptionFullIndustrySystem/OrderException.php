<?php

declare(strict_types=1);

namespace App\Exceptions;

class OrderException extends AppException
{
    public static function notFound(int $id): static
    {
        return new static("Order #$id not found", 404);
    }

    public static function alreadyShipped(int $id): static
    {
        return new static("Order #$id has already been shipped", 409);
    }

    public static function insufficientStock(string $sku, int $requested, int $available): static
    {
        return new static(
            "Insufficient stock for SKU '$sku': requested $requested, available $available",
            422
        );
    }
}
