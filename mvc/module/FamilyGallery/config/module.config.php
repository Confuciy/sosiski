<?php
namespace FamilyGallery;

use Zend\Router\Http\Segment;

return array(
    'router' => array(
        'routes' => array(
            'family-gallery' => array(
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/family-gallery[/:action[/:member_id[/:year[/:month]]]][/]',
                    'constraints' => array(
                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'member_id' => '[0-9]+',
                        'year'      => '[0-9]+',
                        'month'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => Controller\FamilyGalleryController::class,
                        'action'     => 'index',
                        'member_id'  => 0,
                        'year'     	 => 0,
                        'month'      => 0,
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            Controller\FamilyGalleryController::class => Controller\Factory\FamilyGalleryControllerFactory::class,
        ),
    ),
    'access_filter' => [
        'controllers' => [
            Controller\FamilyGalleryController::class => [
                ['actions' => ['index'], 'allow' => '*'],
                ['actions' => ['view'], 'allow' => '@']
            ],
        ]
    ],
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
//        'strategies' => array(
//            'ZfcTwigViewStrategy',
//        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'familygallery_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/FamilyGallery/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'FamilyGallery\Entity' => 'familygallery_entity',
                )
            )
        )
    ),
);