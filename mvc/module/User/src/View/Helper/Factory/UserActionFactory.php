<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\View\Helper\UserAction;

class UserActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $userManager = $container->get(\User\Service\UserManager::class);

        $user = new \User\Controller\UserController($dbAdapter, $userManager);

        return new UserAction($user);
    }
}
