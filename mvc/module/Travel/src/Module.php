<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Travel;

use Travel\Service\TravelManager;

class Module
{
    /**
     * This method returns the path to module.config.php file.
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\TravelController::class => function ($container) {
                    $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
                    $travelManager = $container->get(TravelManager::class);
                    return new Controller\TravelController($dbAdapter, $travelManager);
                },
            ]
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Service\TravelManager::class => function ($container) {
                    $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
                    return new Service\TravelManager($dbAdapter);
                },
            ]
        ];
    }
}
