<?php

trait Logger
{
    public function log(string $message): void
    {
        echo "[LOG]: $message<br>";
    }

    public function debug(string $message): void
    {
        echo "[DEBUG]: $message<br>";
    }
}

class UserService
{
    use Logger {
        log as public;
        debug as protected;
    }

    public function createUser(string $name): void
    {
        $this->log("Creating user: $name");
        $this->debug("Debug info for $name");
    }
}

class AdminService
{
    use Logger {
        debug as private debugInternal;
    }

    public function runDebug(): void
    {
        $this->debugInternal("Admin internal debug");
    }
}

$service = new UserService();

$service->createUser("Alice");

$service->log("External log");

// $service->debug("External debug");
// Fatal error because debug() is protected


$admin = new AdminService();

$admin->log("Admin log");

$admin->runDebug();

// $admin->debugInternal("Test");
// Fatal error because debugInternal() is private

?>