<?php

namespace XmlOutput\Service;

use Zend\ServiceManager\FactoryInterface,
	Zend\ServiceManager\ServiceLocatorInterface,
	XmlOutput\View\Strategy\XmlStrategy;

/**
 * Creates the service for the Xml Strategy.
 */
class ViewXmlStrategyFactory implements FactoryInterface
{
    /**
     * Creates and returns the XML view strategy
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return XmlStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new XmlStrategy($serviceLocator->get('ViewXmlRenderer'));
    }
}