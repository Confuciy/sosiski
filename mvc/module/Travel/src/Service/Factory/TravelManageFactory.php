<?php
namespace Travel\Service\Factory;

use Interop\Container\ContainerInterface;
use Travel\Service\TravelManager;

/**
 * This is the factory class for UserManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class TravelManagerFactory
{
    /**
     * This method creates the UserManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');

        return new TravelManager($dbAdapter);
    }
}
