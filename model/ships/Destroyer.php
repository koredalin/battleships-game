<?php

namespace model\ships;

use model\Ship;

class Destroyer extends Ship {

	public function __construct($name = '') {
		$this->size = 4;
		$this->type = 'Destroyer';
		$this->name = strtoupper(trim($name));
		parent::__construct();
	}

}