<?php
use Zend\Db\TableGateway\TableGateway;
#use Zend\Db\Sql\Select;
#use Zend\Db\Sql\Sql;

error_reporting(E_ERROR);
ini_set("display_errors", 1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

clearstatcache();

if (file_exists('../../vendor/autoload.php')) {
    $loader = include '../../vendor/autoload.php';
}

$adapter = new Zend\Db\Adapter\Adapter(array(
    'driver' => 'Pdo_Mysql',
    'database' => 'confuciy_sosiski',
    'username' => 'confuciy_sosiski',
    'password' => 'KilyhhhRThT',
    'hostname' => 'db35.valuehost.ru',
    'post'     => '3306',
    'charset'  => 'utf8'
));