<?php
trait Greeting
{
    public function greet(): string
    {
        
        return "Hello, I am " . get_class($this);
    }
}

class Admin
{
    use Greeting;
}

class Guest
{
    use Greeting;
}

echo (new Admin())->greet(); 
echo (new Guest())->greet(); 
?>