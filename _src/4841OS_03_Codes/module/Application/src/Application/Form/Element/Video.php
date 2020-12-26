<?php

// Set our namespace just right
namespace Application\Form\Element;

// We need to extend from the base element
use Zend\Form\Element;

// Set the class name, and make sure we extend from the 
// base element
class Video extends Element
{
	// The type of the element is video, 'nuff said.
    protected $attributes = array(
        'type' => 'video',
    );
}
