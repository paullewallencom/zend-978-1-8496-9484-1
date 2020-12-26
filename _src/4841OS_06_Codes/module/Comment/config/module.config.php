<?php

return array(
	// Set up a quick route to our comment output
    'router' => array(
        'routes' => array(
            'comment' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/comment',
                    'defaults' => array(
                        'controller' => 'Comment\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
	
	// Make sure the controllers are invokable by us
    'controllers' => array(
        'invokables' => array(
            'Comment\Controller\Index' => 'Comment\Controller\IndexController'
        ),
    ),
	
	// Add our custom view helper
	'view_helpers' => array(
		'invokables' => array(
			'comments' => 'Comment\View\Helper\Comments',
		),
	),
	
	// Set the path to our view templates
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
