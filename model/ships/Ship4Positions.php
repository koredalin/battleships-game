<?php
/**
 * @game BattleShips
 */

namespace model\ships;

use model\Ship;

/**
 * @model Ship4 extends abstract class Ship
 * The only thing which is not changed in a ship is its length (size).
 * @author Hristo Hristov
 */
class Ship4Positions extends Ship {

	public function __construct($type, $name = '') {
		$this->type = strval(trim($type));
		$this->name = strtoupper(trim($name));
		$this->size = 4;
		parent::__construct();
	}

}