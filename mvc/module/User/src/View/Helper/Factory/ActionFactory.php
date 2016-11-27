<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\View\Helper\Action;

class ActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /*
        $pluginManager = $container->get('Zend\Mvc\Controller\PluginManager');
        $redirectPlugin = $pluginManager->get('redirect');
        var_dump($redirectPlugin);
        die;
        */

        return new Action($container);
    }
}
