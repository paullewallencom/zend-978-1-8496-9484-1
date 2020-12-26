<?php

namespace DAO\Db\DTO;

class Cards
{
	private $id;
	private $color;
	private $type;
	private $value;
	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getColor() {
		return $this->color;
	}

	public function setColor($color) {
		if (in_array($color, array('diamond', 'spade', 'heart', 'club')) === false) {
			throw new \Exception("Type can only be 'diamond', 'spade', 'heart' or 'club'.");
		}
		
		$this->color = $color;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		if (in_array($type, array('number', 'picture')) === false) {
			throw new \Exception("Type can only be 'number' or 'picture'.");
		}
		
		$this->type = $type;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		if (strlen($value) > 6) {
			throw new \Exception("Maximum length of value is 6.");
		}
		
		$this->value = $value;
	}
	
	public function __construct($type, $value, $color, $id = null) 
	{
		if ($id !== null) $this->setId($id);
		
		$this->setColor($color);
		$this->setType($type);
		$this->setValue($value);
	}
}