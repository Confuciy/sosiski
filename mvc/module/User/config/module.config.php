<?php
namespace User;

//use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
//use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/login[/]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/logout[/]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'reset-password' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/reset-password[/]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'resetPassword',
                    ],
                ],
            ],
            'users' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/users[/:action[/:id]][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\UserController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'register' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/register[/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\UserController::class,
                        'action'        => 'register',
                    ],
                ],
            ],
        ],
    ],
//    'controllers' => [
//        'factories' => [
//            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
//            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
//        ],
//    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\UserController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['register', 'resetPassword', 'message', 'setPassword'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
                ['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '@']
            ],
        ]
    ],
//    'service_manager' => [
//        'factories' => [
//            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
//            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
//            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
//            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
//        ],
//    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\UserAction::class => View\Helper\Factory\UserActionFactory::class,
            View\Helper\AuthAction::class => View\Helper\Factory\AuthActionFactory::class,
            #View\Helper\Action::class => InvokableFactory::class,
            View\Helper\Register::class => View\Helper\Factory\RegisterFactory::class,
        ],
        'aliases' => [
            'userViewHelper' => View\Helper\UserAction::class,
            'authViewHelper' => View\Helper\AuthAction::class,
            'userViewHelperRegister' => View\Helper\Register::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];

