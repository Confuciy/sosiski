<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$stationList = new StationList();

$stationList->addStation(new RadioStation(89));
$stationList->addStation(new RadioStation(101));
$stationList->addStation(new RadioStation(102));
$stationList->addStation(new RadioStation(103.2));

foreach($stationList as $station) {
    echo $station->getFrequency() . PHP_EOL . '<br />';
}

$stationList->removeStation(new RadioStation(89)); // Will remove station 89