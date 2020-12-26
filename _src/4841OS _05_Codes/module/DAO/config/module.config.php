<?php

return array(
	/**
	 * NEVER EVER PUT THE ACCESS DETAILS OF YOUR SERVER IN HERE!
	 * 
	 * I just do it to show you how the configuration would work, but this could
	 * be placed better in the config/autoload/local.php
	 */
	'dao' => array(
		'hostname' => 'localhost',
		'username' => 'some_user',
		'password' => 'some_password',
		'database' => 'book',
		
		// The mapper configuration is used for our mapper so that it know which
		// table it should use, ie. __CLASS__ => 'tablename'.
		'mapper' => array(
			'Cards' => 'cards',
		),
	),
	
	// Initialize our service manager
	'service_manager' => array(
	    'invokables' => array(
		    'DAO_Connector' =>'DAO\Db\Connection\Connector',
		    'DAO_Mapper_Cards' =>'DAO\Db\Mapper\Cards',
	    ),
	),
);
