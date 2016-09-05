<?php

namespace controller;

use \model\BattleField as BF;

class ShipPositionsController {

	const maxIterationsPerShipPosSet = 40;

	/**
	 * @class "\model\BattleField"
	 * alias and object
	 */
	const BF = '\model\BattleField';

	protected $bField = null;

	/**
	 * @class "\model\Ship" and inheritors
	 * array of objects
	 */
	protected $ships = array();

	public function __construct() {
		$this->bField = BF::getInstance();
	}

	public function setShipPositions() {
		$this->ships[] = new \model\ships\Destroyer('D1');
		$this->ships[] = new \model\ships\Destroyer('D2');
		$this->ships[] = new \model\ships\BattleShip('BS1');
		foreach ($this->ships as $shipIndex => $oShip) {
			$this->setShipPosition($shipIndex, $oShip->size);
		}
		$this->setAllShipsToSession();
		$this->setBattleFieldToSession();
		$this->loadView();
	}

	private function setShipPosition($shipIndex, $shipSize) {
		$shipSize = (int) $shipSize;
		if ($shipSize <= 0 || $shipSize > constant(self::BF . '::matrixRowsNum') || $shipSize > constant(self::BF . '::matrixColsNum')) {
			return false;
		}
		$shipMat = $this->bField->getShipMatrix();
		$freePositionsNum = 0;
		$freePositions = array();
		$loops = 0;
		while ($freePositionsNum < $shipSize && $loops <= self::maxIterationsPerShipPosSet) {
			if (!$freePositionsNum) {
				$randPos = $this->getShipRandomPosition($shipSize);
				if (!is_array($randPos) || empty($randPos)) {
					throw new \Exception('No random position set for ship with Size ' . $shipSize . '.');
					return false;
				}
				$axisX = $randPos['axisX'];
				$axisY = $randPos['axisY'];
			}

			if ($shipMat[$axisX][$axisY] === constant(self::BF . '::shipMatrixDeployed')) {
				$freePositionsNum = 0;
				$freePositions = array();
			} else {
				$freePositions[] = array('axisX' => $axisX, 'axisY' => $axisY);
				$freePositionsNum++;
				($randPos['axisType'] == 1) ? $axisY++ : $axisX++;
			}
			$loops++;
		}

		if ($shipSize == count($freePositions)) {
			$this->ships[$shipIndex]->position = $freePositions;
			foreach ($freePositions as $pos) {
				$shipMat[$pos['axisX']][$pos['axisY']] = constant(self::BF . '::shipMatrixDeployed');
			}
		} else {
			throw new \Exception('Ship with size ' . $shipSize . ' is not positioned.');
		}

		$this->bField->setShipMatrix($shipMat);
	}

	private function getShipRandomPosition($shipSize) {
		if (($shipSize <= 0)) {
			return array();
		}
		// Random 1 - Horizontal Axis
		// Random 2 - Vertical Axis
		$axisType = rand(1, 2);
		if ($axisType == 1) {
			$axisXNum = rand(1, constant(self::BF . '::matrixRowsNum'));
			$axisX = BF::getRowIndex($axisXNum);
			$axisY = rand(1, (constant(self::BF . '::matrixColsNum') - $shipSize + 1));
		} else {
			$axisY = rand(1, constant(self::BF . '::matrixColsNum'));
			$axisXNum = rand(1, (constant(self::BF . '::matrixRowsNum') - $shipSize + 1));
			$axisX = BF::getRowIndex($axisXNum);
		}

		return array('axisType' => $axisType, 'axisX' => $axisX, 'axisY' => $axisY);
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
		global $interface;
		$tpl = ($interface === 'web') ? 'GameTpl.php' : 'ShellGameTpl.php';
		$t->render($tpl);
	}

}

/*
	if ($shipSize > constant(self::BF . '::matrixRowsNum')) {
		$axisNum = rand(1, constant(self::BF . '::matrixRowsNum'));
	}
/**/