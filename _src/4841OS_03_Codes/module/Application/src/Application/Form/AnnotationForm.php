<?php

namespace Application\Form;

use Zend\Form\Annotation;

/**
 * We want to name this form annotationform, which is why we use the tag below, 
 * defining the name.
 * 
 * @Annotation\Name("annotationform")
 * 
 * A hydrator makes sure our framework can 'read' the properties in our object, 
 * in this case we tell our annotation engine that we have an object that needs 
 * its properties read. There is probably a more technical, accurate way of 
 * explaining it, but let's just keep it to this for now.
 * 
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class AnnotationForm
{
	/**
	 * If we want to exclude properties in our form just use the Exclude annotation.
	 * 
	 * @Annotation\Exclude()
	 */
	public $id;
	
	/**
	 * @Annotation\Filter({"name": "StringTrim"})
	 * @Annotation\Filter({"name": "StripTags"})
	 * @Annotation\Validator({"name": "StringLength", "options":{"min": 5, "max": 50, "encoding": "UTF-8"}})
	 * @Annotation\Required(true)
	 * @Annotation\Attributes({"type": "text", "placeholder": "Your name here...", })
	 * @Annotation\Options({"label": "What is your name?"})
	 */
	public $name;
}