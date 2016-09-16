<?php
/**
 * @game BattleShips
 */

namespace model\ships;

use model\Ship;

/**
 * @model Ship5 extends abstract class Ship
 * The only thing which is not changed in a ship is its length (size).
 * @author Hristo Hristov
 */
class Ship5Positions extends Ship {

	public function __construct($type, $name = '') {
		$this->type = strval(trim($type));
		$this->name = strtoupper(trim($name));
		$this->size = 5;
		parent::__construct();
	}
	
}
