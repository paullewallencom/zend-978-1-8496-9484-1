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

class CommentController extends AbstractActionController
{
    public function forwardAction()
    {
		$view = new ViewModel();
		
		// Get the comments from the index action 
		$comments = $this->forward()
				         ->dispatch(
				'Comment\Controller\Index', 
				array('action' => 'index')
		);
		
		// If we keep this on true it will return an exception, so let us not do that
		$comments->setTerminal(false);
		
		// Return the view model with the comments as child
		return $view->addChild($comments, 'comments');
    }
	
	public function helperAction()
	{
		// Sexy emptiness, nothing to do here
		return new ViewModel();
	}
	
	public function ajaxAction()
	{
		// Still nothing to do, great eh?
		return new ViewModel();
	}
}
