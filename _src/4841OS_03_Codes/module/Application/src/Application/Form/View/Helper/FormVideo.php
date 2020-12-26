<?php

namespace Application\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper,
    Zend\Form\ElementInterface,
    Zend\Form\Exception;

class FormVideo extends AbstractHelper
{
    /**
     * Attributes valid for the video tag
     *
     * @var array
     */
    protected $validTagAttributes = array(
		'autoplay' => true,
		'controls' => true,
		'height' => true,
		'loop' => true,
		'muted' => true,
		'poster' => true,
		'preload' => true,
		'src' => true,
		'width' => true,
    );

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormInput
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

	/**
	 * Creates the <source> element for use in the <video> element.
	 * 
	 * @param array|string $src		Can either be an array of strings, or a string alone.
	 * @return string
	 */
	protected function createSourcesString($src) 
	{
		$retval = '';
		
		if (is_array($src) === true) {
			foreach ($src as $tmpSrc) {
				$retval .= $this->createSourcesString($tmpSrc);
			}
		} else {
			$retval = sprintf(
					'<source src="%s">',
					$src
			);
		}
		
		return $retval;
	}
	
    /**
     * Render a form <video /> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
		// Get the src attribute of the element
        $src = $element->getAttribute('src');
		
		// Check if the src is null or empty, in that case throw an error
		// as we can 't play a video without a video link!
        if ($src === null || $src === '') {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned src; none discovered',
                __METHOD__
            ));
        }

		// Get the attributes from the element
        $attributes = $element->getAttributes();

		// Unset the src as we don't need it right here as we render it separately
		unset($attributes['src']);
		
		// Return our rendered object
        return sprintf(
            '<video %s>%s</video>',
            $this->createAttributesString($attributes),
			$this->createSourcesString($src)
        );
    }
}
