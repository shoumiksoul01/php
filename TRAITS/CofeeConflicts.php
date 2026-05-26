<?php

trait CoffeeMakerTrait
{
    public function boilWater(): string
    {
        return "CoffeeMaker boiling at 90°C";
    }

    public function brew(): string
    {
        return "Brewing coffee...";
    }
}

trait WaterBoilerTrait
{
    public function boilWater(): string
    {
        return "WaterBoiler boiling at 100°C";
    }

    public function pourWater(): string
    {
        return "Pouring hot water...";
    }
}

class SmartAppliance
{
    use CoffeeMakerTrait, WaterBoilerTrait {
        WaterBoilerTrait::boilWater insteadof CoffeeMakerTrait;
        CoffeeMakerTrait::boilWater as boilForCoffee;
    }
}

$appliance = new SmartAppliance();

echo $appliance->boilWater();
echo "<br>";

echo $appliance->boilForCoffee();
echo "<br>";

echo $appliance->brew();
echo "<br>";

echo $appliance->pourWater();

?>