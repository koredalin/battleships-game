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
class ShipPositionsController extends GameController {

	public function __construct() {
		\model\GameStatus::$gameSuccess = 0;
		\model\GameStatus::$hitsCount = 0;
		$this->bField = new \model\BattleField();
	}

	public function setShipPositions() {
		$modelNameSpace = '\model\ships\\';
		$ships[] = array('size' => 4, 'type' => 'Destroyer', 'name' => 'D1');
		$ships[] = array('size' => 4, 'type' => 'Destroyer', 'name' => 'D2');
		$ships[] = array('size' => 5, 'type' => 'BattleShip', 'name' => 'BS1');
		$shipMat = $this->bField->getShipMatrix();
		foreach ($ships as $key => $ship) {
			$className = $modelNameSpace . 'Ship' . $ship['size'] . 'Positions';
			if (!class_exists($className)) {
				throw new \Exception('No Ship with such Size ' . $ship['size'] . '.');
			}
			$this->ships[$key] = new $className($ship['type'], $ship['name']);
			$this->ships[$key]->setShipPosition($shipMat, $ship['size']);
		}
		$this->bField->setShipMatrix($shipMat);
		$this->setAllShipsToSession();
		$this->setBattleFieldToSession();
		$this->loadView();
	}

	private function loadView() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->bField->getHitMatrix();
		$tpl = $this->getTemplate();
		$t->render($tpl);
	}

}
