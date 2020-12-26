<?php

namespace Comment\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Comments extends AbstractHelper
{
    public function __invoke()
    {
		// Instantiate the controller with the comments
		$controller = new \Comment\Controller\IndexController();
		
		// Execute our indexAction to retrieve the ViewModel, and then add the 
		// template of that ViewModel so it renders fine
		$model = $controller->indexAction()
				            ->setTemplate('comment/index/index');
		
		// Now return our rendered view
        return $this->getView()
				    ->render($model);
    }
}