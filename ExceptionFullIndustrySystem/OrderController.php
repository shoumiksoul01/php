<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Exceptions\OrderException;
use App\Exceptions\PaymentException;
use App\Exceptions\UserException;
use App\Http\JsonResponse;
use App\Http\Request;
use App\Services\CheckoutService;
use function App\Http\logger;

class OrderController
{
    public function __construct(
        private readonly CheckoutService $checkout
    ) {}

    public function checkout(Request $request): JsonResponse
    {
        try {
            $order = $this->checkout->placeOrder(
                $request->userId(),
                $request->items(),
                $request->paymentData()
            );

            return new JsonResponse(['order_id' => $order->getId()], 201);

        } catch (UserException $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());

        } catch (OrderException $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());

        } catch (PaymentException $e) {
            logger()->error('Payment failed', [
                'transaction_id' => $e->getTransactionId(),
                'message'        => $e->getMessage(),
            ]);
            return new JsonResponse(['error' => 'Payment failed'], $e->getCode());

        } catch (AppException $e) {
            // Catch-all for any other unhandled domain exception
            logger()->error('Unhandled domain exception', [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ]);
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
