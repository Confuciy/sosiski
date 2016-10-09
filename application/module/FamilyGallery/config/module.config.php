<?php
return array(
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
    /*
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    */
    'translator' => array(
        'locale' => 'en_US', //ru_RU
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'FamilyGallery\Controller\FamilyGalleryController' => 'FamilyGallery\Controller\FamilyGalleryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'family-gallery' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/family-gallery[/:action[/:member_id[/:year[/:month]]]][/]',//'/blog[/][:action][/:id][/]', //'[/blog[/:action[/:id]]][/]'
                    'constraints' => array(
                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'member_id' => '[0-9]+',
                        'year'      => '[0-9]+',
                        'month'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'FamilyGallery\Controller\FamilyGalleryController',
                        'action'     => 'index',
                        'member_id'  => 0,
                        'year'     	 => 0,
                        'mounth'     => 0,
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ZfcTwigViewStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'showMessages' => 'FamilyGallery\View\Helper\ShowMessages',
        ),
    ),
);