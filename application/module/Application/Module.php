<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

//        $eventManager->getSharedManager()->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function ($e) {
//            $serviceManager = $e->getApplication()->getServiceManager();
//            $pluginManager = $serviceManager->get('Zend\Mvc\Controller\PluginManager');
//            $redirectPlugin = $pluginManager->get('redirect');
//            return $redirectPlugin->toRoute('family-gallery', array('controller' => 'FamilyGallery', 'action' => 'view', 'member_id' => 1));
//        }, 100);

        #$translator = $e->getApplication()->getServiceManager()->get('translator');
        #$translator->setLocale('ru_RU');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}