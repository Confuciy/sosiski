<?php
namespace User;

//use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
//    public function onBootstrap(MvcEvent $e)
//    {
//        $eventManager        = $e->getApplication()->getEventManager();
//        $moduleRouteListener = new ModuleRouteListener();
//        $moduleRouteListener->attach($eventManager);
//
//        $application = $e->getTarget();
//        $sm = $application->getServiceManager();
//        $auth = $sm->get('zfcuser_auth_service');
//        if (!$auth->hasIdentity()) {
//            $eventManager = $e->getApplication()->getEventManager();
//            $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e) {
//                $vm = $e->getViewModel();
//                $vm->setTemplate('layout/user');
//            });
//        }
//    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e) {
            $vm = $e->getViewModel();
            $vm->setTemplate('layout/zend_layout');
        });
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
