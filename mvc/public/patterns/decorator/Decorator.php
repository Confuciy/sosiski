<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$someCoffee = new SimpleCoffee();
echo $someCoffee->getCost().'р. | '; // 10
echo $someCoffee->getDescription(); // Simple Coffee
echo '<br />';
$someCoffee = new MilkCoffee($someCoffee);
echo $someCoffee->getCost().'р. | '; // 12
echo $someCoffee->getDescription(); // Simple Coffee, milk
echo '<br />';
$someCoffee = new WhipCoffee($someCoffee);
echo $someCoffee->getCost().'р. | '; // 17
echo $someCoffee->getDescription(); // Simple Coffee, milk, whip
echo '<br />';
$someCoffee = new VanillaCoffee($someCoffee);
echo $someCoffee->getCost().'р. | '; // 20
echo $someCoffee->getDescription(); // Simple Coffee, milk, whip, vanilla