<?php

namespace Application\Model;

class SampleModel 
{
	private $engine;
	private $primary;
	private $text;	
	
	public function getEngine() 
	{
		return $this->engine;
	}

	public function setEngine($engines) 
	{
		$this->engine = $engines;
	}

	public function getPrimary() 
	{
		return $this->primary;
	}

	public function setPrimary($primary) 
	{
		if (is_int($primary) === false) {
			throw new \Exception("Primary ({$primary}) should be an integer!");
		}
		
		$this->primary = $primary;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText($text) 
	{
		$this->text = $text;
	}
}