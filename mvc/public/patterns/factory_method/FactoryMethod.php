<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$devManager = new DevelopmentManager();
$devManager->takeInterview(); // Output: Asking about design patterns
echo '<br />';
$marketingManager = new MarketingManager();
$marketingManager->takeInterview(); // Output: Asking about community building.