<?php

namespace XmlOutput\Service;

use XmlOutput\View\Renderer\XmlRenderer,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the service for the Xml Renderer.
 */
class ViewXmlRendererFactory implements FactoryInterface
{
	/**
     * Creates and returns the XML view renderer
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return XmlRenderer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $renderer = new XmlRenderer();
		
		// Set the View resolvers and helper managers.
        $renderer->setResolver($serviceLocator->get('ViewResolver'));
        $renderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));

        return $renderer;
    }
}