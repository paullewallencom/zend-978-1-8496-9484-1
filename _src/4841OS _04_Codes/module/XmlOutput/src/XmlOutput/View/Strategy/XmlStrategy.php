<?php

namespace XmlOutput\View\Strategy;

use XmlOutput\View\Model\XmlModel,
    XmlOutput\View\Renderer\XmlRenderer,
	Zend\EventManager\EventManagerInterface,
	Zend\EventManager\ListenerAggregateInterface,
	Zend\View\ViewEvent;

/**
 * This is the XML View Strategy
 */
class XmlStrategy implements ListenerAggregateInterface 
{
	/**
	 * @var \Zend\Stdlib\CallbackHandler[]
	 */
	protected $listeners = array();
	
	/**
	 * @var XmlRenderer
	 */
	protected $renderer;
	
	/**
	 * Constructor
	 *
	 * @param  XmlRenderer $renderer
	 */
	public function __construct(XmlRenderer $renderer) 
	{
		$this->renderer = $renderer;
	}
	
	/**
	 * Make sure we only use our renderer when we are also using our XmlModel.
	 *
	 * @param  ViewEvent $e
	 * @return null|XmlRenderer
	 */
	public function selectRenderer(ViewEvent $e) 
	{
		if (!$e->getModel() instanceof XmlModel) {
			// This is not our type of model, can't do anything
			return;
		}
		
		return $this->renderer;
	}
	
	/**
	 * Let's attach the aggregate to the specified event manager
	 *
	 * @param  EventManagerInterface $events
	 * @param  int $priority
	 * @return void
	 */
	public function attach(EventManagerInterface $events, $priority = 1) 
	{
		$this->listeners[] = $events->attach(
				ViewEvent::EVENT_RENDERER, 
				array($this, 'selectRenderer'), 
				$priority
		);
		
		$this->listeners[] = $events->attach(
				ViewEvent::EVENT_RESPONSE, 
				array($this, 'injectResponse'),
				$priority
		);
	}
	
	/**
	 * We can inject the response now with the XML content and the appropriate Content-Type header
	 *
	 * @param  ViewEvent $e
	 * @return void
	 */
	public function injectResponse(ViewEvent $e) 
	{
		if ($e->getRenderer() !== $this->renderer) {
			// The renderer we got is not ours, returning
			return;
		}
		
		$result = $e->getResult();
		
		if (is_string($result) === false) {
			// String is empty, we cannot output anything
			return;
		}
		
		$model = $e->getModel();
		$response = $e->getResponse();
		$response->setContent($result);
		$headers = $response->getHeaders();
		$charset = '; charset='. $model->getEncoding(). ';';
		
		$headers->addHeaderLine('content-type', 'application/xml'. $charset);
	}
	
	/**
	 * We can detach the aggregate listeners from the specified event manager
	 *
	 * @param  EventManagerInterface $events
	 * @return void
	 */
	public function detach(EventManagerInterface $events) 
	{
		foreach($this->listeners as $index => $listener) {
			if($events->detach($listener)) {
				unset($this->listeners[$index]);
			}
		}
	}
}