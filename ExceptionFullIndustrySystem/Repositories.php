<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\User;

interface UserRepositoryInterface
{
    public function findOrFail(int $id): User;
}

interface OrderRepositoryInterface
{
    public function save(\App\Models\Order $order): void;
    public function findById(int $id): ?\App\Models\Order;
}

interface InventoryRepositoryInterface
{
    /**
     * Returns the current stock level for $sku.
     */
    public function getStock(string $sku): int;

    /**
     * Deducts $quantity from the stock for $sku.
     *
     * @throws \App\Exceptions\OrderException  when stock is insufficient
     */
    public function deduct(string $sku, int $quantity): void;
}

// ---------------------------------------------------------------------------
// In-memory implementations (swap for DB-backed ones in production)
// ---------------------------------------------------------------------------

class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var User[] */
    private array $store;

    public function __construct()
    {
        $this->store = [
            1 => new User(1, 'Alice Smith',  'alice@example.com'),
            2 => new User(2, 'Bob Johnson',  'bob@example.com'),
            3 => new User(3, 'Carol White',  'carol@example.com'),
        ];
    }

    public function findOrFail(int $id): User
    {
        return $this->store[$id] ?? throw UserException::notFound($id);
    }
}

class InMemoryOrderRepository implements OrderRepositoryInterface
{
    /** @var \App\Models\Order[] */
    private array $store = [];

    public function save(\App\Models\Order $order): void
    {
        $this->store[$order->getId()] = $order;
    }

    public function findById(int $id): ?\App\Models\Order
    {
        return $this->store[$id] ?? null;
    }
}

class InMemoryInventoryRepository implements InventoryRepositoryInterface
{
    /** @var array<string, int>  sku → quantity */
    private array $stock;

    public function __construct()
    {
        $this->stock = [
            'WIDGET-001' => 50,
            'GADGET-002' => 10,
            'DOOHIC-003' => 0,   // intentionally out of stock for demo
        ];
    }

    public function getStock(string $sku): int
    {
        return $this->stock[$sku] ?? 0;
    }

    public function deduct(string $sku, int $quantity): void
    {
        $available = $this->getStock($sku);

        if ($available < $quantity) {
            throw \App\Exceptions\OrderException::insufficientStock($sku, $quantity, $available);
        }

        $this->stock[$sku] -= $quantity;
    }
}
