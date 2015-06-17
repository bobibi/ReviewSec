<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'ReviewSec\Controller\Web',
                        'action' => 'index'
                    )
                )
            ),
            'web' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/web[/[:action[/[:id[/]]]]]',
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'ReviewSec\Controller\Web',
                        'action' => 'index'
                    )
                )
            ),
            'rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/rest[/[:id[/]]]',
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'ReviewSec\Controller\Rest'
                    )
                )
            ),
            'admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/admin[/[:action[/]]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'ReviewSec\Controller\Admin',
                        'action' => 'index'
                    ),
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'ReviewSec\Controller\Web' => 'ReviewSec\Controller\WebController',
            'ReviewSec\Controller\Rest' => 'ReviewSec\Controller\RestController',
            'ReviewSec\Controller\Admin' => 'ReviewSec\Controller\AdminController',
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/jsonp' => __DIR__ . '/../view/layout/jsonp.phtml',
            'reviewsec/web/index' => __DIR__ . '/../view/review-sec/web/index.phtml',
            'reviewsec/admin/index' => __DIR__ . '/../view/review-sec/admin/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    )
);
