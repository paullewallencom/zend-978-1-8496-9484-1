<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		$view = new ViewModel();
				
		// We can safely assume that once we come here, we are successfully authenticated
		$view->setVariable(
				'identity', 
				$this->getServiceLocator()
				     ->get('AuthService')
				     ->getIdentity()
		);
		
        return $view;
    }
}
