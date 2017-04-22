<?php
if (file_exists('../../../vendor/autoload.php')) {
    $loader = include '../../../vendor/autoload.php';
}

require('UploadHandler.php');
$options = array(
    'upload_dir' => dirname(__FILE__).'/../travels/'.$_GET['travel_id'].'/files/',
    'upload_url' => UploadHandler::get_full_url().'/../travels/'.$_GET['travel_id'].'/files/',
);
$upload_handler = new UploadHandler($options);