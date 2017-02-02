<?php
namespace User;

use Zend\Mvc\MvcEvent;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Session\SessionManager;
use User\Controller\AuthController;
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
        $sharedEventManager->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
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
        if ($controllerName != AuthController::class && !$authManager->filterAccess($controllerName, $actionName)) {

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
            return $controller->redirect()->toRoute('login', [], ['query' => ['redirectUrl' => $redirectUrl]]);
        }
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AuthController::class => function ($container) {
                    $dbAdapter = $container->get(Adapter::class);
                    $authManager = $container->get(AuthManager::class);
                    $authService = $container->get(AuthenticationService::class);
                    $userManager = $container->get(UserManager::class);
                    return new Controller\AuthController($dbAdapter, $authManager, $authService, $userManager);
                },
                Controller\UserController::class => function ($container) {
                    $dbAdapter = $container->get(Adapter::class);
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
                AuthenticationService::class => function ($container) {
                    $sessionManager = $container->get(SessionManager::class);
                    $authStorage = new Session('Zend_Auth', 'session', $sessionManager);
                    $authAdapter = $container->get(AuthAdapter::class);
                    return new AuthenticationService($authStorage, $authAdapter);
                },
                Service\AuthAdapter::class => function ($container) {
                    $dbAdapter = $container->get(Adapter::class);
                    return new Service\AuthAdapter($dbAdapter);
                },
                Service\AuthManager::class => function ($container) {
                    $authenticationService = $container->get(AuthenticationService::class);
                    $sessionManager = $container->get(SessionManager::class);

//                    // Authenticate user if he has a cookie with authentication info
//                    if (!$authenticationService->hasIdentity() and isset($_COOKIE['user_hash']) and $_COOKIE['user_hash'] != ''
//                            and !isset($_POST['email']) and !isset($_POST['password']) and !isset($_POST['remember_me'])) {
//
//                        $authAdapter = $container->get(AuthAdapter::class);
//
//                        $decrypt = $authAdapter->getRSAdecode();
//
//                        $authAdapter->setEmail($decrypt['email']);
//                        $authAdapter->setPassword($decrypt['password']);
//
//                        $authenticationService->authenticate();
//                    }

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
                Service\UserManager::class => function ($container) {
                    $dbAdapter = $container->get(Adapter::class);
                    return new Service\UserManager($dbAdapter);
                },
            ]
        ];
    }
}
