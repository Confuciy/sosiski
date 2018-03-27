<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$door = new Security(new LabDoor());
$door->open('invalid'); // Big no! It ain't possible.
echo '<br />';
$door->open('$ecr@t'); // Opening lab door
$door->close(); // Closing lab door