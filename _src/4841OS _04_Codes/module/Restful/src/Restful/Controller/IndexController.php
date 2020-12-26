<?php

namespace Restful\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController 
{
	// These are the view models we will accept
	protected $acceptCriteria = array(
      'Zend\View\Model\ViewModel' => array(
         'text/html',
      ),
      'Zend\View\Model\JsonModel' => array(
         'application/json',
         'text/json',
      ),
    );
	
	public function indexAction()
	{
		$viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
		
		$viewModel->setVariables(array('output' => array(
			'one' => 'Row, row, row your boat,',
			'two' => 'gently down the stream.',
			'three' => 'Merrily, merrily, merrily, merrily,',
			'four' => 'life is but a dream.',
		)));
		
		return $viewModel;
	}
}