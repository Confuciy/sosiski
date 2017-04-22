<?php
if (file_exists('../../vendor/autoload.php')) {
    $loader = include '../../vendor/autoload.php';
}

\Tinify\setKey("0dl-M4GFe_MUu_cCC9WphHJFM84Js2WA");
$source = \Tinify\fromFile("IMG_7519.jpg");
$source->toFile("op_IMG_7519.jpg");