<?php
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
#use Zend\Db\Sql\Sql;

error_reporting(E_ERROR);
ini_set("display_errors", 1);

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

$galleryTable = new TableGateway('family_gallery', $adapter);
//$rowset = $galleryTable->select(array('id' => 1));
//$galleryRow = $rowset->current();

/*
$rowset = $galleryTable->select(function (Select $select) {
    $select->order('title ASC')->limit(2);
});

foreach ($rowset as $row){
    echo '<pre>'; print_r($row); echo '</pre>';
}

$data = array(
    'title'=> '111'
);
$galleryTable->insert($data);

echo '<pre>'; print_r($otherTable); echo '</pre>';

echo '<br /><hr /><br />';
*/