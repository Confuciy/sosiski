<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\View\Helper\AuthAction;

class AuthActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $authManager = $container->get(\User\Service\AuthManager::class);
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        $userManager = $container->get(\User\Service\UserManager::class);

        $authController = new \User\Controller\AuthController($dbAdapter, $authManager, $authService, $userManager);

        return new AuthAction($authController);
    }
}
