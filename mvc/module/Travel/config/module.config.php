<?php
namespace Travel;

//use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'travels' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/travels[/:page][/]',
                    'constraints' => [
                        'page' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\TravelController::class,
                        'action'        => 'index',
                        'page'          => 1,
                    ],
                ],
            ],
            'travels_view' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/travels/:url[/]',
                    'constraints' => [
                        'url'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\TravelController::class,
                        'action'        => 'view',
                    ],
                ],
            ],
            'travels_admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/travels/admin[/:action][/:id][/]',
                    'constraints' => [
                        'action'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'  => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\TravelAdminController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed
            // under the 'access_filter' config key, and access is denied to any not listed
            // action for not logged in users. In permissive mode, if an action is not listed
            // under the 'access_filter' key, access to it is permitted to anyone (even for
            // not logged in users. Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\TravelController::class => [
                // Allow anyone to visit "index" and "about" actions
                ['actions' => ['index', 'year', 'view'], 'allow' => '*'],
                // Allow authorized users to visit "settings" action
                //['actions' => ['settings'], 'allow' => '@']
            ],
            Controller\TravelAdminController::class => [
                // Allow anyone to visit "index" and "about" actions
                ['actions' => ['index', 'add', 'edit'], 'allow' => '@'],
                // Allow authorized users to visit "settings" action
                //['actions' => ['settings'], 'allow' => '@']
            ],
        ]
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\TravelAction::class => View\Helper\Factory\TravelActionFactory::class,
        ],
        'aliases' => [
            'travelViewHelper' => View\Helper\TravelAction::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'uploadPath' => dirname(__FILE__).'/../../../public/img/travels/',
];

