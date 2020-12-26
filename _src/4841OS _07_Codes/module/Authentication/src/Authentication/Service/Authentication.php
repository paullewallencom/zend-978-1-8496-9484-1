<?php

namespace Authentication\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\Authentication\Adapter\DbTable as AuthDbTable, 
	Zend\Authentication\Storage\Session;

class Authentication implements ServiceLocatorAwareInterface
{
	private $servicelocator;
	
	public function getServiceLocator() 
	{
		return $this->servicelocator;
	}

	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) 
	{
		$this->servicelocator = $serviceLocator;
	}	
	
	/**
	 * Lets us know if we are authenticated or not.
	 * 
	 * @return boolean
	 */
	public function isAuthenticated()
	{
		// Check if the authentication session is empty, if not we assume we 
		// are authenticated
		$session = new Session();
		
		return !$session->isEmpty();
	}
	
	/**
	 * Authenticates the user against the Authentication adapter.
	 * 
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function authenticate($username, $password)
	{
		$authentication = new AuthDbTable(
				$this->getServiceLocator()->get('db'),
				'users',
				'username',
				'password'
		);
		
		// We use md5 in here because SQLite doesn't have any functionality to
		// encrypt strings
		$result = $authentication->setIdentity($username)
				                 ->setCredential(md5($password))
				                 ->authenticate();
		
		if ($result->isValid() === true) {
			// Now save the identity to the session
			$session = new Session();
			$session->write($result->getIdentity());
		}
		
		return $result->isValid();
	}
	
	/**
	 * Gets the identity of the user, if available, otherwise returns false.
	 * @return array
	 */
	public function getIdentity()
	{
		// Clear out the session, we are done here
		$session = new Session();
		
		if ($session->isEmpty() === false) {
			return $session->read();
		} else {
			return false;
		}
	}
	
	/**
	 * Logs the user out by clearing the session.
	 */
	public function logout()
	{
		// Clear out the session, we are done here
		$session = new Session();
		$session->clear();
	}
}