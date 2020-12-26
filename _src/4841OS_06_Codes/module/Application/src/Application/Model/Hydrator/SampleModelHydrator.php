<?php
 
namespace Application\Model\Hydrator;

use Zend\Stdlib\Hydrator\AbstractHydrator;

class SampleModelHydrator extends AbstractHydrator 
{
	private $mapping = array(
		'id' => 'primary',
		'value' => 'engine',
		'description' => 'text',
	);
	
	public function extract($object) 
	{
		// If we are not receiving an object, throw an exception
		if (is_object($object) === false) {
			throw new \Exception("We expect object to be an actual object!");
		}
		
		$return = array();
		
		foreach ($this->mapping as $key=>$map) {
			// Build the getter method from our property
			$getter = 'get'. ucfirst($map);
			
			// Get the property value from the object and filter it
			$return[$key] = $this->extractValue($key, $object->$getter());
		}
		
		return $return;
	}

	public function hydrate(array $data, $object) 
	{
		// If we are not receiving an object, throw an exception
		if (is_object($object) === false) {
			throw new \Exception("We expect object to be an actual object!");
		}
		
		// Loop through the properties and values 
		foreach ($data as $property=>$value) {
			// Check if the property exists in our mapping
			if (array_key_exists($property, $this->mapping) === true) {
				// Build the setter method from our property
				$setter = 'set'. ucfirst($this->mapping[$property]);
				
				// Set the value of the property
				$object->$setter($this->hydrateValue($this->mapping[$property], $value));
			}
		}
		
		// Now return our hydrated object
		return $object;
	}
}
