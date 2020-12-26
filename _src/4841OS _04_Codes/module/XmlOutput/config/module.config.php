<?php

return array(
	// Set our factories, so our service manager can find them
	'service_manager' => array( 
		'factories' => array( 
			'ViewXmlStrategy' => 'XmlOutput\Service\ViewXmlStrategyFactory', 
            'ViewXmlRenderer' => 'XmlOutput\Service\ViewXmlRendererFactory'
		), 
	), 
	
	// Add our strategy to the view manager for our output
    'view_manager' => array(
		'strategies' => array(
            'ViewXmlStrategy',
        ),
	),
);
