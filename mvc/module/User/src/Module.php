<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use User\Controller\AuthController;
use Zend\Session\SessionManager;
use User\Service\AuthAdapter;
use User\Service\AuthManager;
use User\Service\UserManager;

class Module
{
    /**
     * This method returns the path to module.config.php file.
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * This method is called once the MVC bootstrapping is complete and allows
     * to register event listeners.
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    /**
     * Event listener method for the 'Dispatch' event. We listen to the Dispatch
     * event to call the access filter. The access filter allows to determine if
     * the current visitor is allowed to see the page or not. If he/she
     * is not authorized and is not allowed to see the page, we redirect the user
     * to the login page.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Get the instance of AuthManager service.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Execute the access filter on every controller except AuthController
        // (to avoid infinite redirect).
        if ($controllerName != AuthController::class &&
            !$authManager->filterAccess($controllerName, $actionName)) {

            // Remember the URL of the page the user tried to access. We will
            // redirect the user to that URL after successful login.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Make the URL relative (remove scheme, user info, host name and port)
            // to avoid redirecting to other domain by a malicious user.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // Redirect the user to the "Login" page.
            return $controller->redirect()->toRoute('login', [], ['query'=>['redirectUrl'=>$redirectUrl]]);
        }
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AuthController::class => function($container) {
                    $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
                    $authManager = $container->get(AuthManager::class);
                    $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
                    $userManager = $container->get(UserManager::class);
                    return new Controller\AuthController($dbAdapter, $authManager, $authService, $userManager);
                },
                Controller\UserController::class => function($container) {
                    $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
                    $userManager = $container->get(UserManager::class);
                    return new Controller\UserController($dbAdapter, $userManager);
                },
            ]
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                \Zend\Authentication\AuthenticationService::class => function($container) {
                    $sessionManager = $container->get(SessionManager::class);
                    $authStorage = new \Zend\Authentication\Storage\Session('Zend_Auth', 'session', $sessionManager);
                    $authAdapter = $container->get(AuthAdapter::class);
                    return new \Zend\Authentication\AuthenticationService($authStorage, $authAdapter);
                },
                Service\AuthAdapter::class => function($container) {
                    $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
                    return new Service\AuthAdapter($dbAdapter);
                },
                Service\AuthManager::class => function($container) {
                    $authenticationService = $container->get(\Zend\Authentication\AuthenticationService::class);
                    $sessionManager = $container->get(SessionManager::class);

                    if(!$authenticationService->hasIdentity() and isset($_COOKIE['email']) and $_COOKIE['email'] != ''){
                        $authAdapter = $container->get(AuthAdapter::class);
                        $authAdapter->setEmail($_COOKIE['email']);
                        $user = $authAdapter->getUser();
                        if(isset($user['password']) and $user['password'] != '' and isset($user['status']) and $user['status'] == 1){
                            $authAdapter->setPassword($user['password']);
                            $authAdapter->setPasswordVerifyType(1);
                            $result = $authenticationService->authenticate();
                        }
                    }
                    #echo '<pre>'; print_r($sessionManager); echo '</pre>';

                    // Get contents of 'access_filter' config key (the AuthManager service
                    // will use this data to determine whether to allow currently logged in user
                    // to execute the controller action or not.
                    $config = $container->get('Config');
                    if (isset($config['access_filter']))
                        $config = $config['access_filter'];
                    else
                        $config = [];
                    return new Service\AuthManager($authenticationService, $sessionManager, $config);
                },
                Service\UserManager::class => function($container) {
                    $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
                    return new Service\UserManager($dbAdapter);
                },
            ]
        ];
    }
}
