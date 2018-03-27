<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$darkTheme = new DarkTheme();

$about = new About($darkTheme);
$careers = new Careers($darkTheme);

echo $about->getContent(); // "About page in Dark Black";
echo '<br />';
echo $careers->getContent(); // "Careers page in Dark Black";