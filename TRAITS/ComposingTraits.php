<?php
trait Loggable
{
    public function log(string $msg): void
    {
        echo "[LOG] $msg\n";
    }
}

trait Timestampable
{
    public function getTimestamp(): string
    {
        return date('Y-m-d H:i:s');
    }
}


trait Auditable
{
    use Loggable, Timestampable;

    public function audit(string $action): void
    {
        $this->log("[{$this->getTimestamp()}] Action: $action");
    }
}

class OrderService
{
    use Auditable;
}

$service = new OrderService();
$service->audit("Order #123 placed"); 
$service->log("Direct log");           
$service->getTimestamp();
?>
