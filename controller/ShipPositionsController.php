<?php

namespace controller;

use \model\BattleField as BF;

class ShipPositionsController implements ShipPositionsInterface {

	const MAX_ITERATIONS_PER_SHIP_POS_SET = 40;

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
		if ($shipSize <= 0 || $shipSize > constant(self::BF . '::MATRIX_ROWS_NUM') || $shipSize > constant(self::BF . '::MATRIX_COLS_NUM')) {
			return false;
		}
		$shipMat = $this->bField->getShipMatrix();
		$freePositionsNum = 0;
		$freePositions = array();
		$loops = 0;
		while ($freePositionsNum < $shipSize && $loops <= self::MAX_ITERATIONS_PER_SHIP_POS_SET) {
			if (!$freePositionsNum) {
				$randPos = $this->getShipRandomPosition($shipSize);
				if (!is_array($randPos) || empty($randPos)) {
					throw new \Exception('No random position set for ship with Size ' . $shipSize . '.');
					return false;
				}
				$axisX = $randPos['axisX'];
				$axisY = $randPos['axisY'];
			}

			if ($shipMat[$axisX][$axisY] === constant(self::BF . '::SHIP_MATRIX_DEPLOYED')) {
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
				$shipMat[$pos['axisX']][$pos['axisY']] = constant(self::BF . '::SHIP_MATRIX_DEPLOYED');
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
			$axisXNum = rand(1, constant(self::BF . '::MATRIX_ROWS_NUM'));
			$axisX = BF::getRowIndex($axisXNum);
			$axisY = rand(1, (constant(self::BF . '::MATRIX_COLS_NUM') - $shipSize + 1));
		} else {
			$axisY = rand(1, constant(self::BF . '::MATRIX_COLS_NUM'));
			$axisXNum = rand(1, (constant(self::BF . '::MATRIX_ROWS_NUM') - $shipSize + 1));
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
	if ($shipSize > constant(self::BF . '::MATRIX_ROWS_NUM')) {
		$axisNum = rand(1, constant(self::BF . '::MATRIX_ROWS_NUM'));
	}
/**/