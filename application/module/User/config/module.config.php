<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'zfcuser_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/User/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'zfcuser_entity',
                )
            )
        )
    ),

    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'User\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'zfcuser' => __DIR__ . '/../view'
        ),
        'strategies' => array(
            'ZfcTwigViewStrategy',
        ),
    ),

//    'controllers' => array(
//        'invokables' => array(
//            'user' => 'User/Controller/ZfcUserController',
//        ),
//    ),
//
//    'router' => array(
//        'routes' => array(
//            'zfcuser' => array(
//                'type' => 'Literal',
//                'child_routes' => array(
//                    'login' => array(
//                        'type' => 'Literal',
//                        'options' => array(
//                            'route' => '/login',
//                            'defaults' => array(
//                                'controller' => 'user',
//                                'action'     => 'login',
//                            ),
//                        ),
//                    ),
//                    'register' => array(
//                        'type' => 'Literal',
//                        'options' => array(
//                            'route' => '/register',
//                            'defaults' => array(
//                                'controller' => 'user',
//                                'action'     => 'register',
//                            ),
//                        ),
//                    ),
//                ),
//            ),
//        ),
//    ),

    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'User\Entity\Role',
            ),
        ),
    ),
);
