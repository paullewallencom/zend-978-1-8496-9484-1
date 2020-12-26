<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use XmlOutput\View\Model\XmlModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new XmlModel(array(
			"some_variable" => "Awesome!",
			"why_not_another_one" => "While we are here?"
		));
    }
}
