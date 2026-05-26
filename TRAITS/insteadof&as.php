<?php

trait TraitA
{
    public function hello(): string
    {
        return "Hello from TraitA";
    }
}

trait TraitB
{
    public function hello(): string
    {
        return "Hello from TraitB";
    }
}

class MyClass
{
    use TraitA, TraitB {
        TraitA::hello insteadof TraitB;
    }
}

echo (new MyClass())->hello();
echo "<br>";

class MyClass2
{
    use TraitA, TraitB {
        TraitB::hello insteadof TraitA;
        TraitA::hello as helloFromA;
    }
}

$obj = new MyClass2();

echo $obj->hello();
echo "<br>";

echo $obj->helloFromA();

?>