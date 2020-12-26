<?php

namespace DAO\Db\Connection;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
	Zend\Db\Adapter\Adapter;

class Connector implements ServiceLocatorAwareInterface 
{
	protected $serviceLocator;

	/**
	 * Initializes a connection and returns a fresh adapter.
	 * 
	 * @return \Zend\Db\Adapter\Adapter
	 * @throws \Exception
	 */
	public function initialize()
	{
		// Get the configuration
		$dao = $this->getServiceLocator()->get('config');
		
		// Check if everything is there in the configuration
		foreach (array('hostname', 'username', 'database', 'password') as $required) {
			if (in_array($required, array_keys($dao['dao'])) === false) {
				throw new \Exception("{$required} is not in the DAO configuration!");
			}
		}
		
		// We can assume we have everything, now set up our MySQL connection
		return new Adapter(array(
			'driver' => 'Pdo_Mysql',
			'database' => $dao['dao']['database'],
			'hostname' => $dao['dao']['hostname'],
			'username' => $dao['dao']['username'],
			'password' => $dao['dao']['password'],
		));
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}
}