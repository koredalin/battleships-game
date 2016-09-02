<?php

namespace model\ships;

use model\Ship;

class BattleShip extends Ship {

	protected function __construct($name = '') {
		$this->size = 5;
		$this->type = 'BattleShip';
		$this->name = strtoupper(trim($name));
	}

}
