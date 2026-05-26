<?php

trait Greetable
{
    abstract protected function getDisplayName(): string;

    public function greet(): string
    {
        return "Hello, " . $this->getDisplayName() . "!";
    }
}

class Admin
{
    use Greetable;

    protected function getDisplayName(): string
    {
        return "Admin User";
    }
}

class Guest
{
    use Greetable;

    protected function getDisplayName(): string
    {
        return "Guest";
    }
}

echo (new Admin())->greet();
echo "<br>";
echo (new Guest())->greet();

?>