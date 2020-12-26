<?php

namespace Application\Form; 

use Zend\InputFilter\Factory as InputFilterFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 

class NormalFormValidator implements InputFilterAwareInterface 

{ 
    protected $inputFilter; 
    
    public function setInputFilter(InputFilterInterface $inputFilter) 
    { 
		// We want to make sure that we cannot set an input filter, as we
		// already do that ourselves
        throw new \Exception("Cannot set input filter."); 
    } 
    
    public function getInputFilter() 
    { 
		// If our input filter doesn't exist yet, create one
        if ($this->inputFilter === null) 
        {
            $inputFilter = new InputFilter(); 
            $factory = new InputFilterFactory(); 
            
			$inputFilter->add($factory->createInput(array(
				'name' => 'name', 
				'required' => true, 
				'filters' => array( 
					array('name' => 'StripTags'), 
					array('name' => 'StringTrim'), 
				), 
				'validators' => array( 
					array ( 
						'name' => 'StringLength', 
						'options' => array( 
							'encoding' => 'UTF-8', 
							'min' => '5', 
							'max' => '50', 
						), 
					), 
				), 
			)));
 
			$inputFilter->add($factory->createInput(array(
				'name' => 'gender', 
				'filters' => array( 
					array('name' => 'StripTags'), 
					array('name' => 'StringTrim'), 
				), 
				'validators' => array( 
				), 
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'password', 
				'filters' => array( 
					array('name' => 'StripTags'), 
					array('name' => 'StringTrim'), 
				), 
				'validators' => array( 
					array ( 
						'name' => 'StringLength', 
						'options' => array( 
							'encoding' => 'UTF-8', 
							'min' => '5', 
						), 
					), 
				), 
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'password_verify', 
				'filters' => array( 
					array('name' => 'StripTags'), 
					array('name' => 'StringTrim'), 
				), 
				'validators' => array( 
					array ( 
						'name' => 'identical', 
						'options' => array( 
							'token' => 'password', 
						), 
					), 

				), 
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'email', 
				'filters' => array( 
					array('name' => 'StripTags'), 
					array('name' => 'StringTrim'), 
				), 
				'validators' => array( 
					array ( 
						'name' => 'StringLength', 
						'options' => array( 
							'encoding' => 'UTF-8', 
							'min' => '5', 
							'max' => '250', 
						), 
					), 
					array ( 
						'name' => 'EmailAddress', 
						'options' => array( 
							'messages' => array( 
								'emailAddressInvalidFormat' => 'Your email seems to be invalid', 
							) 
						), 
					), 
					array ( 
						'name' => 'NotEmpty', 
						'options' => array( 
							'messages' => array( 
								'isEmpty' => 'I am sorry, your email is required', 
							) 
						), 
					), 
				), 
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'description', 
				'required' => false, 
				'filters' => array( 
					array('name' => 'StripTags'), 
					array('name' => 'StringTrim'), 
				), 
				'validators' => array( 
				), 
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'birthdate', 
				'required' => true, 
				'filters' => array( 
					array('name' => 'StripTags'), 
					array('name' => 'StringTrim'), 
				), 
				'validators' => array( 
					array(
						'name' => 'Between',
						'options' => array(
							'min' => '1900-01-01', 
							'max' => '2013-01-01', 
						),
					),
				), 
			)));
 
            $this->inputFilter = $inputFilter;
			
			unset($factory, $inputFilter);
        }
		
		return $this->inputFilter;
    } 
} 