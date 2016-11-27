<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\View\Helper\Register;
//use User\Service\UserManager;
//use User\Controller\UserController;

class RegisterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
//        if (method_exists($container, 'getServiceLocator')) {
//            $container = $container->getServiceLocator() ?: $container;
//        }
        #$userManager = $container->get(UserManager::class);
        #echo '<pre>';
        #var_dump($userManager->ff());
        #die;
        //$dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        //$navManager = $container->get(NavManager::class);

        // Get menu items.
        //$items = $navManager->getMenuItems();

//        $user = 'User\View\Helper\Factory\RegisterFactory';
        //$user = $container->get(\User\Controller\UserController::class);

//        $configTable = $container->get('Config\Model\ConfigTable');

        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $userManager = $container->get(\User\Service\UserManager::class);

        $user = new \User\Controller\UserController($dbAdapter, $userManager);

        return new Register($user);

        // Instantiate the helper.
        //return new Register($user);
    }
}
