<?php
/**
 * @game BattleShips
 */

namespace model\ships;

use model\Ship;

/**
 * @model Destroyer extends abstract class Ship
 * @author Hristo Hristov
 */
class Destroyer extends Ship {

	public function __construct($name = '') {
		$this->name = strtoupper(trim($name));
		parent::__construct();
	}
	
	protected function getShipType() {
		return 'Destroyer';
	}
	
	protected function getShipSize() {
		return 4;
	}

}