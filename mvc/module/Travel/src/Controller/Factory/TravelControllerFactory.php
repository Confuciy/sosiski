<?php
namespace Travel\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Travel\Controller\TravelController;
use Travel\Service\TravelManager;

/**
 * This is the factory for UserController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class TravelControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $travelManager = $container->get(TravelManager::class);

        // Instantiate the controller and inject dependencies
        return new TravelController($dbAdapter, $travelManager);
    }
}
