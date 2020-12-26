<?php

namespace DAO\Db\Mapper;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
	Zend\Db\Sql\Sql;

class MapperAbstract implements ServiceLocatorAwareInterface 
{
	/**
	 * @var Zend\Db\Sql\Sql
	 */
	private $connection;
	
	/**
	 * @var Zend\ServiceManager\ServiceLocatorInterface 
	 */
	protected $serviceLocator;

	/**
	 * This class gets the connection with the database and returns a fresh
	 * Sql object.
	 * 
	 * @return Zend\Db\Sql\Sql
	 * @throws \Exception
	 */
	protected function connection()
	{
		if ($this->connection === null) {
			$dao = $this->getServiceLocator()->get('config');
			$class = explode('\\', get_class($this));

			if (isset($dao['dao']['mapper']) === true && isset($dao['dao']['mapper'][end($class)]) === true) {
				// Get the database adapter
				$adapter = $this->getServiceLocator()
								->get('DAO_Connector')
								->initialize();

				// We have a configuration, now return our SQL object with the right
				// table name included
				$this->connection = new Sql($adapter, $dao['dao']['mapper'][end($class)]);
			} else {
				throw new \Exception("Configuration dao\mapper\\". end($class). " not set.");
			}
		} 
		
		return $this->connection;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}
}
