<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$original = new Sheep('Jolly');
echo $original->getName(); // Jolly
echo '<br />';
echo $original->getCategory(); // Mountain Sheep

echo '<br /><br />';

// Clone and modify what is required
$cloned = clone $original;
$cloned->setName('Dolly');
echo $cloned->getName(); // Dolly
echo '<br />';
echo $cloned->getCategory(); // Mountain sheep