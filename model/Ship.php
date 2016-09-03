<?php
namespace model;

class Ship {
	protected $size = 0;
	protected $position = array();
	protected $type = '';
	protected $name = '';
	
	protected function __construct() {
		
	}
	
	public function __get($propName) {
		$propName = strval(trim($propName));
		if (!in_array($propName, array('size', 'position', 'type', 'name'), true)) {
			return false;
		}
		
		return $this->$propName;
	}
	
	public function __set($propName, $propValue) {
		$propName = strval(trim($propName));
		if (!in_array($propName, array('size', 'position', 'type', 'name'), true)) {
			return false;
		}
		$this->$propName = $propValue;
		
		return $propValue;
	}
}
