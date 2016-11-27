<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Action extends AbstractHelper
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function act($controllerName, $controllerNameFactory, $actionName, $params = array())
    {
        #$controllerLoader = $this->container->get(Zend\Controller\CotrollerLoader::class);
        #$controllerLoader->setInvokableClass($controllerName, $controllerName);
        #$controller = $controllerLoader->get($controllerName);
        #return $controller->$actionName($params);

        #die($controllerName.' - '.$controllerNameFactory);

        $controllerLoader = $this->container->get('Zend\ServiceManager\ServiceManager');
        //$controllerLoader->setInvokableClass($controllerName, $controllerName);
        //$controllerLoader->setInvokableClass($controllerName, $controllerNameFactory);
        //$controllerLoader->setFactory($controllerNameFactory);
        //$controller = $controllerLoader->get($controllerName);

        var_dump($controllerLoader->get($controllerName));
        die;

        return $controller->$actionName();
        #die('asdasdasdasdasdas');

        #$pluginManager = $this->container->get('Zend\Mvc\Controller\PluginManager');
        #$redirectPlugin = $pluginManager->get('redirect');
        #return $redirectPlugin->toRoute('users', array('controller' => 'User', 'action' => 'index'));

//        echo '<pre>';
//        var_dump($this->container);
//        die('asdasdasdasdasdas');

        /*
        $controllerLoader = $this->serviceLocator->getServiceLocator()->get('ControllerLoader');
        $controllerLoader->setInvokableClass($controllerName, $controllerName);
        $controller = $controllerLoader->get($controllerName);
        return $controller->$actionName($params);
        */

        /*
        $eventManager = $this>event->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->getSharedManager()->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function ($e) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $pluginManager = $serviceManager->get('Zend\Mvc\Controller\PluginManager');
            $redirectPlugin = $pluginManager->get('redirect');
            return $redirectPlugin->toRoute('family-gallery', array('controller' => 'FamilyGallery', 'action' => 'view', 'member_id' => 1));
        }, 100);
        */
    }
}
