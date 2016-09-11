<?php
/**
 * @game BattleShips
 */

namespace model;

/**
 * @model Ship - abstract class
 * @author Hristo Hristov
 */
abstract class Ship {
	
	protected $type = '';
	protected $size = 0;
	protected $name = '';
	protected $position = array();
	
	protected function __construct() {
		$this->type = $this->getShipType();
		$this->size = $this->getShipSize();
	}
	
	/**
	 * Magic method __get()
	 * @param type $propName
	 * @return Ship class properties or "false" if theere is no such property.
	 */
	public function __get($propName) {
		$propName = strval(trim($propName));
		if (!in_array($propName, array('size', 'position', 'type', 'name'), true)) {
			return false;
		}
		
		return $this->$propName;
	}
	
	/**
	 * Magic method __set()
	 * Sets Ship class a new $this->position or $this->name properties.
	 * @param type $propName - String
	 * @param type $propValue - Array, String
	 * Class properties $this->type and $this->size are set on the object construction only.
	 * @return boolean
	 */
	public function __set($propName, $propValue) {
		$propName = strval(trim($propName));
		if (!in_array($propName, array('position', 'name'), true)) {
			return false;
		}
		$this->$propName = $propValue;
		
		return $propValue;
	}
	
	abstract protected function getShipType();
	abstract protected function getShipSize();
}
