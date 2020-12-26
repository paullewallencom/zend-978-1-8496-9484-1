<?php

// We define our namespace here
namespace Application\Form; 

// We need to use this to create an extend 
use Zend\Form\Form; 

// Starting class definition, extending from Zend\Form
class NormalForm extends Form 
{ 
	// Define our constructor that sets up our elements 
    public function __construct($name = null) 
    { 
		// Create the form with the following name/id
        parent::__construct($name); 
        
		// We want to POST this form to our application
        $this->setAttribute('method', 'post'); 
        
		// Adding a simple input text field
        $this->add(array( 
			// Specifying the name of the field
            'name' => 'name', 
			
			// The type of field we want to show
            'type' => 'Zend\Form\Element\Text', 
			
			// Any extra attributes we can give the element
            'attributes' => array( 
				// If there is no text we will display the placeholder
                'placeholder' => 'Your name here...', 
				
				// Tell the validator if the element is required or not
                'required' => 'required', 
            ), 
			
			// Any extra options we can define
            'options' => array( 
				
				// What is the label we want to give this element
                'label' => 'What is your name?', 
            ), 
        )); 
		
        $this->add(array( 
            'name' => 'gender', 
            'type' => 'Zend\Form\Element\Radio', 
            'attributes' => array( 
                'required' => 'required', 
				
				// Set the initial value to 0, which is "Rather not say"
                'value' => '0', 
            ), 
            'options' => array( 
                'label' => 'What is your gender?', 
                'value_options' => array(
                    '0' => 'Rather not say', 
                    '1' => 'Female', 
                    '2' => 'Male', 
                ),
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'password', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'placeholder' => 'Password here...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Set a password:', 
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'password_verify', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'placeholder' => 'Verify password here...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Verify Password', 
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'email', 
            'type' => 'Zend\Form\Element\Email', 
            'attributes' => array( 
                'placeholder' => 'Type your email here...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'What is your email address?', 
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'description', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
            ), 
            'options' => array( 
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'birthdate', 
            'type' => 'Zend\Form\Element\Date', 
            'attributes' => array( 
                'placeholder' => 'Let us know how old you are...', 
                'required' => 'required', 
				
				// This will be the minimum and maximum dates we want to be able
				// to select
                'min' => '1900-01-01', 
                'max' => '2010-01-01', 
                'step' => '1', 
            ), 
            'options' => array( 
                'label' => 'When were you born? (yyyy-mm-dd)',
            ), 
        )); 
 
		$this->add(array( 
            'name' => 'submit', 
            'type' => 'Zend\Form\Element\Submit',
			'attributes' => array(
				'value' => 'Submit'
			)
        )); 
		
		// This is our CSRF (Cross-Site Request Forgery), which means we want to
		// make sure this form is only posted once. We would always use this to
		// protect ourselves and the user from outside attacks.
        $this->add(array( 
            'name' => 'csrf', 
            'type' => 'Zend\Form\Element\Csrf', 
        ));        
    } 
} 