<?php
use Zend\Crypt\PublicKey\RsaOptions;

$rsaOptions = new RsaOptions(array(
    'pass_phrase' => '@#$@Rgdfbfgh56548())8VVBn!@!##@$%+__)((7""S~!23//'
));
$rsaOptions->generateKeys(array(
    'private_key_bits' => 2048,
));
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/mvc/keys/private_key.pem', $rsaOptions->getPrivateKey());
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/mvc/keys/public_key.pub', $rsaOptions->getPublicKey());