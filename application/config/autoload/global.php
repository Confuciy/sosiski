<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use DoctrineORMModule\Service\ConfigurationFactory as DoctrineConfigurationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=confuciy_sosiski;host=db35.valuehost.ru',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
//    'db' => array(
//        'driver'         => 'Pdo',
//        'dsn' => 'pgsql:host=db35.valuehost.ru;port=5432;user=confuciy_so_p;password=KilyhhhRThT',
//    ),
//    'service_manager' => array(
//        'factories' => array(
//            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
//        ),
//    ),
    'module_layouts' => array(
//        'Admin' => 'layout/admin',
        'ZfcUser' => 'layout/user',
    ),
);
