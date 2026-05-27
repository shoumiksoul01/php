<?php

declare(strict_types=1);

// ---------------------------------------------------------------------------
// Autoloader (no Composer needed for this self-contained demo)
// ---------------------------------------------------------------------------
spl_autoload_register(function (string $class): void {
    $map = [
        'App\\Exceptions\\AppException'     => __DIR__ . '/src/Exceptions/AppException.php',
        'App\\Exceptions\\UserException'    => __DIR__ . '/src/Exceptions/UserException.php',
        'App\\Exceptions\\OrderException'   => __DIR__ . '/src/Exceptions/OrderException.php',
        'App\\Exceptions\\PaymentException' => __DIR__ . '/src/Exceptions/PaymentException.php',

        'App\\Models\\User'        => __DIR__ . '/src/Models/User.php',
        'App\\Models\\OrderItem'   => __DIR__ . '/src/Models/OrderItem.php',
        'App\\Models\\Order'       => __DIR__ . '/src/Models/Order.php',
        'App\\Models\\PaymentData' => __DIR__ . '/src/Models/PaymentData.php',

        'App\\Gateway\\GatewayException'        => __DIR__ . '/src/Gateway/GatewayException.php',
        'App\\Gateway\\GatewayTimeoutException'  => __DIR__ . '/src/Gateway/GatewayException.php',
        'App\\Gateway\\PaymentGatewayInterface'  => __DIR__ . '/src/Gateway/PaymentGateway.php',
        'App\\Gateway\\FakePaymentGateway'       => __DIR__ . '/src/Gateway/PaymentGateway.php',

        'App\\Repositories\\UserRepositoryInterface'      => __DIR__ . '/src/Repositories/Repositories.php',
        'App\\Repositories\\OrderRepositoryInterface'     => __DIR__ . '/src/Repositories/Repositories.php',
        'App\\Repositories\\InventoryRepositoryInterface' => __DIR__ . '/src/Repositories/Repositories.php',
        'App\\Repositories\\InMemoryUserRepository'       => __DIR__ . '/src/Repositories/Repositories.php',
        'App\\Repositories\\InMemoryOrderRepository'      => __DIR__ . '/src/Repositories/Repositories.php',
        'App\\Repositories\\InMemoryInventoryRepository'  => __DIR__ . '/src/Repositories/Repositories.php',

        'App\\Services\\CheckoutService'            => __DIR__ . '/src/Services/CheckoutService.php',

        'App\\Http\\Request'      => __DIR__ . '/src/Http/Http.php',
        'App\\Http\\JsonResponse' => __DIR__ . '/src/Http/Http.php',
        'App\\Http\\Logger'       => __DIR__ . '/src/Http/Http.php',

        'App\\Http\\Controllers\\OrderController' => __DIR__ . '/src/Http/Controllers/OrderController.php',
    ];

    if (isset($map[$class])) {
        require_once $map[$class];
    }
});

// Also pull in the free logger() function defined in Http.php
require_once __DIR__ . '/src/Http/Http.php';

use App\Gateway\FakePaymentGateway;
use App\Http\Controllers\OrderController;
use App\Http\Request;
use App\Repositories\InMemoryInventoryRepository;
use App\Repositories\InMemoryOrderRepository;
use App\Repositories\InMemoryUserRepository;
use App\Services\CheckoutService;

// ---------------------------------------------------------------------------
// Wire up the object graph (use a DI container in a real app)
// ---------------------------------------------------------------------------
$gateway    = new FakePaymentGateway();
$controller = new OrderController(
    new CheckoutService(
        new InMemoryUserRepository(),
        new InMemoryOrderRepository(),
        new InMemoryInventoryRepository(),
        $gateway
    )
);

// ---------------------------------------------------------------------------
// Helper
// ---------------------------------------------------------------------------
function run(string $label, OrderController $ctrl, array $payload, string $scenario = 'success'): void
{
    FakePaymentGateway::$scenario = $scenario;
    \App\Http\Logger::flush();

    echo PHP_EOL . str_repeat('─', 60) . PHP_EOL;
    echo "  SCENARIO: $label" . PHP_EOL;
    echo str_repeat('─', 60) . PHP_EOL;

    $response = $ctrl->checkout(new Request($payload));

    echo "  HTTP " . $response->getStatus() . PHP_EOL;
    echo $response->toJson() . PHP_EOL;
}

// ---------------------------------------------------------------------------
// Base payload
// ---------------------------------------------------------------------------
$goodPayload = [
    'user_id' => 1,
    'items'   => [
        ['sku' => 'WIDGET-001', 'name' => 'Widget Pro',  'quantity' => 2, 'unit_price' => 29.99],
        ['sku' => 'GADGET-002', 'name' => 'Gadget Plus', 'quantity' => 1, 'unit_price' => 59.99],
    ],
    'payment' => [
        'intent_id'  => 'pi_ABC123',
        'card_token' => 'tok_visa_success',
        'currency'   => 'USD',
    ],
];

// ---------------------------------------------------------------------------
// Run all scenarios
// ---------------------------------------------------------------------------

// 1. Happy path
run('Successful checkout', $controller, $goodPayload, 'success');

// 2. Unknown user
$unknownUser = array_merge($goodPayload, ['user_id' => 999]);
run('Unknown user (404)', $controller, $unknownUser, 'success');

// 3. Out-of-stock item
$outOfStock = $goodPayload;
$outOfStock['items'][0] = [
    'sku' => 'DOOHIC-003', 'name' => 'Doohickey', 'quantity' => 1, 'unit_price' => 9.99,
];
run('Out-of-stock item (422)', $controller, $outOfStock, 'success');

// 4. Payment gateway timeout
run('Gateway timeout (408)', $controller, $goodPayload, 'timeout');

// 5. Generic gateway error
run('Gateway error (503)', $controller, $goodPayload, 'error');
