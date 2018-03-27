<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$computer = new ComputerFacade(new Computer());
$computer->turnOn(); // Ouch! Beep beep! Loading.. Ready to be used!
echo '<br />';
$computer->turnOff(); // Bup bup buzzz! Haah! Zzzzz