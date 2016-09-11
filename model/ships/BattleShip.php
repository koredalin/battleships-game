<?php
/**
 * @game BattleShips
 */

namespace model\ships;

use model\Ship;

/**
 * @model BattleShip extends abstract class Ship
 * @author Hristo Hristov
 */
class BattleShip extends Ship {

	public function __construct($name = '') {
		$this->name = strtoupper(trim($name));
		parent::__construct();
	}
	
	protected function getShipType() {
		return 'BattleShip';
	}
	
	protected function getShipSize() {
		return 5;
	}
}
