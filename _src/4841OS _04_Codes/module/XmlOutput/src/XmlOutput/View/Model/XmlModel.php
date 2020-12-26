<?php

namespace XmlOutput\View\Model;

use Zend\View\Model\ViewModel;

/**
 * This is the XML View Model
 */
class XmlModel extends ViewModel 
{
	
	/**
	 * XML probably won't need to be captured into a
	 * a parent container by default.
	 *
	 * @var string
	 */
	protected $captureTo = null;
	
	/**
	 * XML is usually terminal
	 *
	 * @var bool
	 */
	protected $terminate = true;
	
	/**
	 * UTF-8 Default Encoding
	 * @var string
	 */
	protected $encoding = 'utf-8';
	
	/**
	 * Content Type Header
	 * @var string
	 */
	protected $contentType = 'application/xml';
	
	/**
	 * Set the encoding
	 * @param string $encoding
	 * @return XmlModel
	 */
	public function setEncoding($encoding) 
	{
		$this->encoding = $encoding;
		return $this;
	}
	
	/**
	 * Get the encoding
	 * @return string
	 */
	public function getEncoding()
	{
		return $this->encoding;
	}
	
	/**
	 * Set the content type
	 * 
	 * @param string $contentType
	 * @return XmlModel
	 */
	public function setContentType($contentType) 
	{
		$this->encoding = $contentType;
		return $this;
	}
	
	/**
	 * Get the content type
	 * 
	 * @return string
	 */
	public function getContentType() 
	{
		return $this->contentType;
	}
	
}