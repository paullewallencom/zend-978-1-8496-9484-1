<?php

namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController, 
	Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
	public function loginAction()
	{
		// See if we are trying to authenticate
		if ($this->params()->fromPost('username') !== null) {
			// Try to authenticate
			$done = $this->getServiceLocator()
					     ->get('AuthService')
					     ->authenticate(
					$this->params()->fromPost('username'),
					$this->params()->fromPost('password')
			);
			
			if ($done === true) {
				$this->redirect()
					 ->toRoute('application');
			} else {
				\Zend\Debug\Debug::dump("Username/password unknown!");
			}
		}
		
		return new ViewModel();
	}
	
	public function logoutAction()
	{
		$this->getServiceLocator()
			 ->get('AuthService')
		     ->logout();
		
		$this->redirect()
			 ->toRoute('authentication');
	}
	
	public function certificateAction() 
	{
		// Create a new custom adapter
		$adapter = new \Authentication\Adapter\Certificate();
		
		// Set the db adapter in our authentication adapter
		$adapter->setDbAdapter($this->getServiceLocator()->get('db'));
		
		// Uncomment this to force HTTPS if we are unable to use it on our server
		//$_SERVER['HTTPS'] = true;
		
		// Uncomment to just get our certificate from our data folder instead
		// of getting it from the browser (expires in August 2024)
		//$certificate = file_get_contents('data/ClientCertificate.crt');
		//$adapter->setCertificate($certificate);
		
		// Try to authenticate
		if ($adapter->authenticate() === false) {
			\Zend\Debug\Debug::dump($adapter->getErrorMessage(), 'Ooops, error - ');
		} else {
			\Zend\Debug\Debug::dump($adapter->getIdentity(), '<b>Successfully authenticated</b> - ');
		}
		
		return $this->response;
	}
}
