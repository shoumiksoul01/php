<?php

declare(strict_types=1);

namespace App\Gateway;

use RuntimeException;

/**
 * Base exception thrown by the payment gateway client.
 */
class GatewayException extends RuntimeException {}

/**
 * Thrown when the gateway does not respond within the allowed time.
 */
class GatewayTimeoutException extends GatewayException {}
