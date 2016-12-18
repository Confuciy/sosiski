<?php
namespace Travel\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Travel\View\Helper\TravelAction;

class TravelActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $travelManager = $container->get(\Travel\Service\TravelManager::class);

        $travelController = new \Travel\Controller\TravelController($dbAdapter, $travelManager);

        return new TravelAction($travelController);
    }
}
