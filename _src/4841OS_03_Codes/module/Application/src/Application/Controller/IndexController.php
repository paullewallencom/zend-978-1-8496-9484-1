<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
		
    // Our empty sample model where we 'save' the data
	Application\Model\SampleModel,
		
	// We only use these for the normal form
	Application\Form\NormalForm,
	Application\Form\NormalFormValidator,
		
    // We only use these for the Annotated Form
	Application\Form\AnnotationForm,
	Zend\Form\Annotation\AnnotationBuilder;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		/**
		 * If we want to use our annotated form, just uncomment the following 
		 * code, and comment out the code that assigns our NormalForm.
		 */
		//$builder = new AnnotationBuilder();
		//$annotationForm = new AnnotationForm();
		//$form = $builder->createForm($annotationForm);
		
		// Initialize our form
        $form = new NormalForm(); 
		
		// Set our request in a local variable for easier access
		$request = $this->getRequest(); 

		if ($request->isPost() === true) {
			// Create a new form validator
			$formValidator = new NormalFormValidator(); 
            
            // Set the input filter of the form to the form validator
            $form->setInputFilter($formValidator->getInputFilter()); 
				
            // Set the data from the post to the form
            $form->setData($request->getPost()); 
			

			// Checkif with the form validator if the form is valid or not
			if ($form->isValid() === true) { 
				// Do some Model stuff, like saving
				$user = new SampleModel(); 
				
				// Get the filtered data from the form
				$user->doStuff($form->getData()); 
				
				// Done with this, unset it
				unset($user);
			}
		}
		
		// Return the view model to the user
		return new ViewModel(array(
            'form' => $form
        ));
	}
	
	public function videoAction()
	{
	}
}
