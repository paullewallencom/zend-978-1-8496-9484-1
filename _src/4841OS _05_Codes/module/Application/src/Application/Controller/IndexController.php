<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Application\Model\Sample;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		$sample = new Sample();
		$sample->simpleQueries();
		$sample->advancedQueries();
		
        return new ViewModel();
    }
}
