<?php

/**
 * @game BattleShips
 */

namespace controller;

/**
 * @controller ShipPositionsController
 *
 * @author Hristo Hristov
 */
use \model\BattleField as BF;

class ShipPositionsController {

	/**
	 * @class "\model\BattleField"
	 * alias and object
	 */
	const BF = '\model\BattleField';

	private $bField = null;

	/**
	 * @class "\model\Ship" and inheritors
	 * array of objects
	 */
	private $ships = array();

	public function __construct() {
		\model\GameStatus::$gameSuccess = 0;
		\model\GameStatus::$hitsCount = 0;
		global $interface;
		/**
		 * The shell don't destroys the static variables.
		 */
		($interface === 'shell') ? BF::destroy() : false;
		$this->bField = BF::getInstance();
	}

	public function setShipPositions() {
		$modelNameSpace = '\model\ships\\';
		$ships[] = array('size' => 4, 'type' => 'Destroyer', 'name' => 'D1');
		$ships[] = array('size' => 4, 'type' => 'Destroyer', 'name' => 'D2');
		$ships[] = array('size' => 5, 'type' => 'BattleShip', 'name' => 'BS1');
		$shipMat = $this->bField->getShipMatrix();
		foreach ($ships as $key => $ship) {
			$className = $modelNameSpace . 'Ship' . $ship['size'];
			if (!class_exists($className)) {
				throw new \Exception('No Ship with such Size ' . $ship['size'] . '.');
			}
			$this->ships[$key] = new $className($ship['type'], $ship['name']);
			$this->ships[$key]->setShipPosition($shipMat, $key, $ship['size']);
		}
		$this->bField->setShipMatrix($shipMat);
		$this->setAllShipsToSession();
		$this->setBattleFieldToSession();
		$this->loadView();
	}

	private function setAllShipsToSession() {
		global $session;
		$session['ships'] = $this->ships;
	}

	private function setBattleFieldToSession() {
		global $session;
		$session['BattleField'] = $this->bField;
	}

	private function loadView() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->bField->getHitMatrix();
		$tpl = $this->getTemplate();
		$t->render($tpl);
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

}
