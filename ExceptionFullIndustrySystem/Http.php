<?php

declare(strict_types=1);

namespace App\Http;

use App\Models\PaymentData;

// ---------------------------------------------------------------------------
// Minimal HTTP primitives (replace with your framework's equivalents)
// ---------------------------------------------------------------------------

class Request
{
    public function __construct(private readonly array $data) {}

    public function userId(): int
    {
        return (int) ($this->data['user_id'] ?? 0);
    }

    /** @return array<array{sku: string, name: string, quantity: int, unit_price: float}> */
    public function items(): array
    {
        return $this->data['items'] ?? [];
    }

    public function paymentData(): PaymentData
    {
        $p = $this->data['payment'] ?? [];

        return new PaymentData(
            $p['intent_id']  ?? 'INTENT-UNKNOWN',
            $p['card_token'] ?? '',
            $p['currency']   ?? 'USD'
        );
    }
}

class JsonResponse
{
    public function __construct(
        private readonly array $body,
        private readonly int   $status = 200
    ) {}

    public function getStatus(): int  { return $this->status; }
    public function getBody(): array  { return $this->body; }

    public function toJson(): string
    {
        return json_encode($this->body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}

// ---------------------------------------------------------------------------
// Minimal PSR-3-like logger
// ---------------------------------------------------------------------------

class Logger
{
    /** @var array<array{level: string, message: string, context: array}> */
    private static array $log = [];

    public function error(string $message, array $context = []): void
    {
        self::$log[] = ['level' => 'ERROR', 'message' => $message, 'context' => $context];
        // In production you'd write to a file / monitoring service here
        fwrite(STDERR, "[ERROR] $message " . json_encode($context) . PHP_EOL);
    }

    public function info(string $message, array $context = []): void
    {
        self::$log[] = ['level' => 'INFO', 'message' => $message, 'context' => $context];
        fwrite(STDOUT, "[INFO]  $message " . json_encode($context) . PHP_EOL);
    }

    /** Returns all captured log entries (handy for tests). */
    public static function all(): array { return self::$log; }
    public static function flush(): void { self::$log = []; }
}

function logger(): Logger
{
    static $instance = null;
    return $instance ??= new Logger();
}
