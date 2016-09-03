<?php

namespace controller;

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
		$this->bField = \model\BattleField::getInstance();
	}

	public function action() {
		$this->ships[] = new \model\ships\Destroyer();
		$this->ships[] = new \model\ships\Destroyer();
		$this->ships[] = new \model\ships\BattleShip();
		foreach ($this->ships as $shipIndex => $oShip) {
			$this->setShipPosition($shipIndex, $oShip->size);
		}
		$this->setAllShipsToSession();
		$this->setBattleFieldToSession();
	}

	protected function getRowIndex($noteNum) {
		$noteNum = (int) $noteNum;
		if ($noteNum < 1) {
			return 'A';
		}
		($noteNum > constant(self::BF . '::matrixRowsNum')) ? $noteNum = constant(self::BF . '::matrixRowsNum') : false;
		$firstNoteAsciiNum = ord('A');
		$currNoteAsciiNum = $firstNoteAsciiNum + $noteNum;
		$currNote = chr($currNoteAsciiNum);

		return strtoupper(trim($currNote));
	}

	protected function getShipRandomPosition($shipSize) {
		if (($shipSize <= 0)) {
			return array();
		}
		// Random 1 - Horizontal Axis
		// Random 2 - Vertical Axis
		$axisType = rand(1, 2);
		if ($axisType == 1) {
			$axisXNum = rand(1, constant(self::BF . '::matrixRowsNum'));
			$axisX = $this->getRowIndex($axisXNum);
			$axisY = rand(1, (constant(self::BF . '::matrixColsNum') - $shipSize + 1));
		} else {
			$axisY = rand(1, constant(self::BF . '::matrixColsNum'));
			$axisXNum = rand(1, (constant(self::BF . '::matrixRowsNum') - $shipSize + 1));
			$axisX = $this->getRowIndex($axisXNum);
		}

		return array('axisType' => $axisType, 'axisX' => $axisX, 'axisY' => $axisY);
	}

	public function setShipPosition($shipIndex, $shipSize) {
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

			if ($shipMat[$axisX][$axisY]) {
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

	public function setAllShipsToSession() {
		$_SESSION['ships'] = $this->ships;
	}

	public function setBattleFieldToSession() {
		$_SESSION['BattleField'] = $this->bField;
	}

	public function loadView() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->bField->getHitMatrix();
//		print_r($t->printMatrix);
		$t->render('GameTpl.php');
	}

}

/*
	if ($shipSize > constant(self::BF . '::matrixRowsNum')) {
		$axisNum = rand(1, constant(self::BF . '::matrixRowsNum'));
	}
/**/