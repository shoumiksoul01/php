<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PaymentException;
use App\Gateway\GatewayException;
use App\Gateway\GatewayTimeoutException;
use App\Gateway\PaymentGatewayInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentData;
use App\Models\User;
use App\Repositories\InventoryRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\UserRepositoryInterface;

class CheckoutService
{
    public function __construct(
        private readonly UserRepositoryInterface      $users,
        private readonly OrderRepositoryInterface     $orders,
        private readonly InventoryRepositoryInterface $inventory,
        private readonly PaymentGatewayInterface      $gateway
    ) {}

    /**
     * Place an order for $userId with the given $items, charging $payment.
     *
     * @param  array<array{sku: string, name: string, quantity: int, unit_price: float}> $items
     *
     * @throws \App\Exceptions\UserException    when the user is not found
     * @throws \App\Exceptions\OrderException   when stock is insufficient
     * @throws PaymentException                 when the payment fails
     */
    public function placeOrder(int $userId, array $items, PaymentData $payment): Order
    {
        // 1. Resolve user — throws UserException::notFound if missing
        $user = $this->users->findOrFail($userId);

        // 2. Validate stock & build order — throws OrderException::insufficientStock
        $order = $this->buildOrder($user, $items);

        // 3. Charge the gateway, mapping low-level gateway exceptions to domain ones
        try {
            $this->gateway->charge($payment, $order->getTotal());
        } catch (GatewayTimeoutException $e) {
            throw PaymentException::timeout($payment->getIntentId());
        } catch (GatewayException $e) {
            throw PaymentException::gatewayError($payment->getIntentId(), $e);
        }

        // 4. Commit stock deductions and persist the order
        foreach ($order->getItems() as $item) {
            $this->inventory->deduct($item->getSku(), $item->getQuantity());
        }

        $this->orders->save($order);

        return $order;
    }

    /**
     * Validate stock levels and construct the Order aggregate.
     *
     * @param  array<array{sku: string, name: string, quantity: int, unit_price: float}> $rawItems
     */
    private function buildOrder(User $user, array $rawItems): Order
    {
        $orderItems = [];

        foreach ($rawItems as $raw) {
            $sku       = $raw['sku'];
            $quantity  = $raw['quantity'];
            $available = $this->inventory->getStock($sku);

            if ($available < $quantity) {
                throw \App\Exceptions\OrderException::insufficientStock($sku, $quantity, $available);
            }

            $orderItems[] = new OrderItem(
                $sku,
                $raw['name'],
                $quantity,
                (float) $raw['unit_price']
            );
        }

        return new Order($user, $orderItems);
    }
}
