<?php

trait Brewable
{
    public function brew(): string
    {
        return "Brewing coffee...";
    }
}

trait Boilable
{
    public function boilWater(): string
    {
        return "Boiling water at 100°C";
    }
}

trait Pourable
{
    public function pourWater(): string
    {
        return "Pouring hot water...";
    }
}
class SmartAppliance
{
    use Brewable, Boilable, Pourable;
}
class CoffeeMachine
{
    use Brewable, Boilable;
}

class Kettle
{
    use Boilable, Pourable;
}

class FullKitchenBot
{
    use Brewable, Boilable, Pourable;
}
$appliance = new SmartAppliance();
echo $appliance->brew();      // Brewing coffee...
echo $appliance->boilWater(); // Boiling water at 100°C
echo $appliance->pourWater(); // Pouring hot water...
$coffeeMachine = new CoffeeMachine();
echo $coffeeMachine->brew();      // Brewing coffee...
echo $coffeeMachine->boilWater(); // Boiling water at 100°C
$kettle = new Kettle();
echo $kettle->boilWater(); // Boiling water at 100°C
echo $kettle->pourWater(); // Pouring hot water...
$kitchenBot = new FullKitchenBot();
echo $kitchenBot->brew();      // Brewing coffee...
echo $kitchenBot->boilWater(); // Boiling water at 100°C
echo $kitchenBot->pourWater(); // Pouring hot water...
