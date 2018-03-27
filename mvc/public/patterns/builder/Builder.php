<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', true);

spl_autoload_register(function($class){
    include $class.'.php';
});

$burger = (new BurgerBuilder(14))
    ->addPepperoni()
    ->addLettuce()
    ->addTomato()
    ->build();

$burger_info = $burger->getBurgerInfo();
echo 'Burger [size '.$burger_info['size'].']<br />
Ingridients: '.implode(', ', $burger_info['ingridients']);