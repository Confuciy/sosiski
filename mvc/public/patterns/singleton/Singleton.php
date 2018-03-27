<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$president1 = President::getInstance();
$president2 = President::getInstance();

var_dump($president1 === $president2); // true