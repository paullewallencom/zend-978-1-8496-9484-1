<?php

return array(
	'service_manager' => array(
		'factories' => array(
		  'Zend\Db\Adapter\Adapter' =>   
				'Zend\Db\Adapter\AdapterServiceFactory',
		),
		'aliases' => array(
		  'db' => 'Zend\Db\Adapter\Adapter',
		),
		'invokables' => array(
			'AuthService' => 'Authentication\Service\Authentication',
		),
	),
	'db' => array(
		'driver' => 'Pdo_Sqlite',
		'database' => ':memory:',
	),
	'router' => array(
        'routes' => array(
            'authentication' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/authentication',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Authentication\Controller',
                        'controller'    => 'Index',
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:action]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Authentication\Controller\Index' => 'Authentication\Controller\IndexController'
        ),
    ),
	'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);