<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\AuthAdapter;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory class for AuthAdapter service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AuthAdapterFactory implements FactoryInterface
{
    /**
     * This method creates the AuthAdapter service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');

        return new AuthAdapter($dbAdapter);
    }
}
