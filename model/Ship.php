<?php
namespace model;

class Ship {
	protected $size = 0;
	protected $position = array();
	protected $type = '';
	protected $name = '';
	
	protected function __construct() {
		
	}
	
	public function __get($name) {
		$name = strval(trim($name));
		if (!in_array($name, array('size', 'position', 'type', 'name'), true)) {
			return false;
		}
		
		return $this->$name;
	}
}
