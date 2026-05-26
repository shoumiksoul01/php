<?php
class ParentClass
{
    public function hello(): string
    {
        return "Hello from ParentClass";
    }
}

trait HelloTrait
{
    public function hello(): string
    {
        return "Hello from Trait";
    }
}


class ChildA extends ParentClass
{
    use HelloTrait;
   
}

echo (new ChildA())->hello();
class ChildB extends ParentClass
{
    use HelloTrait;

    public function hello(): string
    {
        return "Hello from ChildB";
    }
    
}

echo (new ChildB())->hello(); 


class ChildC extends ParentClass
{
    public function hello(): string
    {
        return "Hello from ChildC";
    }
}

echo (new ChildC())->hello(); 
?>