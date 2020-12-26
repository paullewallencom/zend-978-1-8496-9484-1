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
use Application\Model\Hydrator\SampleModelHydrator;
use Application\Model\Hydrator\Strategy\SampleHydratorStrategy;
use Application\Model\SampleModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		// Create the sample model
		$model = new SampleModel();
		
		// Define our sample data
		$data = array(
			'id' => 'Some Id',
			'value' => 'Some Awesome Value',
			'description' => 'Pecunia non olet',
		);
		
		\Zend\Debug\Debug::dump($data, 'Input array:');
		
		// Initialize the Hydrator
		$hydrator = new SampleModelHydrator();
		
		// Add some hydrator strategies that makes sure that our primary key
		// is an integer. Commenting this out will return an exception, try it!
		$hydrator->addStrategy("primary", new SampleHydratorStrategy());
		
		// Hydrate our model
		$newObject = $hydrator->hydrate($data, $model);
		\Zend\Debug\Debug::dump($newObject, 'Hydration w/ strategy:');
		
		// Now extract all the data again
		\Zend\Debug\Debug::dump($hydrator->extract($newObject), 'Extraction:');
		
        return $this->response;
    }
	
	public function serviceAction()
	{
		\Zend\Debug\Debug::dump(
				$this->getServiceLocator()
				     ->get('ExampleService')
				     ->encodeMyString("Service? Easily created!"),
				"Rot13'ing my string the old fashioned way -" 
		);
		
		return $this->response;
	}
}
