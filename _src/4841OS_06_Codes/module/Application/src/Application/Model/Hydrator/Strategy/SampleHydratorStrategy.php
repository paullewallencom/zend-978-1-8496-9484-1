<?php

namespace Application\Model\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class SampleHydratorStrategy implements StrategyInterface
{
	public function extract($value) 
	{
		if (is_int($value) === true) {
			return (int)$value;
		} else {
			return rand(0, 10000);
		}
	}

	public function hydrate($value) 
	{
		if (is_int($value) === true) {
			return (int)$value;
		} else {
			return rand(0, 10000);
		}
	}	
}