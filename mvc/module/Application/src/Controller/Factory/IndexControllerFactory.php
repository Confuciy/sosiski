<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;
use Application\Service\PaymentManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $paymentManager = $container->get(PaymentManager::class);
        $translator = $container->get('Zend\Mvc\I18n\Translator');
        //$authenticationService = $container->get(\Zend\Authentication\AuthenticationService::class);

        // Instantiate the controller and inject dependencies
        return new IndexController($dbAdapter, $paymentManager, $translator); //, $authenticationService
    }
}
