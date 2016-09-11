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
class Ship5 extends Ship {

	public function __construct($type, $name = '') {
		$this->type = strval(trim($type));
		$this->name = strtoupper(trim($name));
		parent::__construct();
	}
	
	protected function getShipSize() {
		return 5;
	}
}
