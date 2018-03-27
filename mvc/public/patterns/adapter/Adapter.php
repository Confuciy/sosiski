<?php
spl_autoload_register(function($class){
    include $class.'.php';
});
$wildDog = new WildDog();
$wildDogAdapter = new WildDogAdapter($wildDog);

$hunter = new Hunter();
$hunter->hunt($wildDogAdapter);