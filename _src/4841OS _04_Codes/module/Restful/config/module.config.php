<?php

return array(
	// Set up a basic route
	'router' => array(
        'routes' => array(
            'restful' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/restful',
                    'defaults' => array(
                        'controller' => 'Restful\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
		),
	),
	
	// Initialize our controllers
	'controllers' => array(
        'invokables' => array(
            'Restful\Controller\Index' => 'Restful\Controller\IndexController'
        ),
    ),
	
	// Add the JSON strategy to the view manager for our output
    'view_manager' => array(
		'strategies' => array(
            'ViewJsonStrategy',
        ),
		'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
	),
);
