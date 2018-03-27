<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$androidBuilder = new AndroidBuilder();
$androidBuilder->build();
echo '<br />';

// Output:
// Running android tests
// Linting the android code
// Assembling the android build
// Deploying android build to server

$iosBuilder = new IosBuilder();
$iosBuilder->build();

// Output:
// Running ios tests
// Linting the ios code
// Assembling the ios build
// Deploying ios build to server