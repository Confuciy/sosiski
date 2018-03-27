<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$door = DoorFactory::makeDoor(100, 200);
echo 'Width: ' . $door->getWidth();
echo '<br />';
echo 'Height: ' . $door->getHeight();