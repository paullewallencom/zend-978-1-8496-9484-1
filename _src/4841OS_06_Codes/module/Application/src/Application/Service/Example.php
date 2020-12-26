<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
	Zend\ServiceManager\ServiceLocatorInterface;

class Example implements ServiceLocatorAwareInterface 
{
	protected $serviceLocator;

	// This is set by our initialization so we don't actually have to do this 
	// ourselves probably
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) 
	{
		$this->serviceLocator = $serviceLocator;
	}

	// Retrieve the service locator, handy if we want to read some configuration
	public function getServiceLocator() 
	{
		return $this->serviceLocator;
	}
	
	// Let's create a simple string to rot13 encoder as an example
	public function encodeMyString($string)
	{
		return str_rot13($string);
	}
}