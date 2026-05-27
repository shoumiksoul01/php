<?php

declare(strict_types=1);

namespace App\Models;

class Order
{
    private static int $nextId = 1;

    private readonly int $id;
    private string $status = 'pending';

    /** @param OrderItem[] $items */
    public function __construct(
        private readonly User  $user,
        private readonly array $items
    ) {
        $this->id = self::$nextId++;
    }

    public function getId(): int      { return $this->id; }
    public function getUser(): User   { return $this->user; }
    public function getStatus(): string { return $this->status; }

    /** @return OrderItem[] */
    public function getItems(): array { return $this->items; }

    public function getTotal(): float
    {
        return array_reduce(
            $this->items,
            fn(float $carry, OrderItem $item) => $carry + $item->getSubtotal(),
            0.0
        );
    }

    public function markShipped(): void
    {
        $this->status = 'shipped';
    }
}
