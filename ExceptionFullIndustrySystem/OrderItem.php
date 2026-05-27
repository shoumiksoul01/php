<?php

declare(strict_types=1);

namespace App\Models;

class OrderItem
{
    public function __construct(
        private readonly string $sku,
        private readonly string $name,
        private readonly int    $quantity,
        private readonly float  $unitPrice
    ) {}

    public function getSku(): string      { return $this->sku; }
    public function getName(): string     { return $this->name; }
    public function getQuantity(): int    { return $this->quantity; }
    public function getUnitPrice(): float { return $this->unitPrice; }
    public function getSubtotal(): float  { return $this->unitPrice * $this->quantity; }
}
