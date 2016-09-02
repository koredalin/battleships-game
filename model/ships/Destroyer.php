<?php

namespace model\ships;

use model\Ship;

class Destroyer extends Ship {

	protected function __construct($name = '') {
		$this->size = 4;
		$this->type = 'Destroyer';
		$this->name = strtoupper(trim($name));
	}

}