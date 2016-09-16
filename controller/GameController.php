<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller;

/**
 * Description of Controller
 *
 * @author Hristo
 */
class GameController {

	protected $bField = null;

	/**
	 * @class "\model\Ship" and inheritors
	 * array of objects
	 */
	protected $ships = array();
	
	protected function __construct() {
		
	}

	/**
	 * Returns game template.
	 * @return string
	 */
	protected function getTemplate() {
		global $interface;
		if ($interface === 'web') {
			return 'GameTpl.php';
		}
		if ($interface === 'shell_commands') {
			return 'ShellCommandsGameTpl.php';
		}

		return 'ShellGameTpl.php';
	}

	protected function setAllShipsToSession() {
		global $session;
		$session['ships'] = $this->ships;
	}

	protected function setBattleFieldToSession() {
		global $session;
		$session['BattleField'] = $this->bField;
	}
	
}
