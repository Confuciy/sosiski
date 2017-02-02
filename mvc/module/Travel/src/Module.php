<?php
namespace Travel;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\I18n\Translator;
use Travel\Service\TravelManager;
use User\Service\UserManager;

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
                    $dbAdapter = $container->get(Adapter::class);
                    $travelManager = $container->get(TravelManager::class);
                    return new Controller\TravelController($dbAdapter, $travelManager);
                },
                Controller\TravelAdminController::class => function ($container) {
                    $dbAdapter = $container->get(Adapter::class);
                    $travelManager = $container->get(TravelManager::class);
                    $userManager = $container->get(UserManager::class);
                    $translator = $container->get(Translator::class);
                    return new Controller\TravelAdminController($dbAdapter, $travelManager, $userManager, $translator);
                },
            ]
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Service\TravelManager::class => function ($container) {
                    $dbAdapter = $container->get(Adapter::class);
                    $translator = $container->get(Translator::class);
                    $config = $container->get('Config');
                    return new Service\TravelManager($dbAdapter, $translator, $config['uploadPath']);
                },
            ]
        ];
    }
}
