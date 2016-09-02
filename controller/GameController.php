<?php

namespace controller;

class GameController {

	const matrixRowsNum = 10;
	const matrixColsNum = 10;
	const shipMatrixBlank = 0;
	const shipMatrixDeployed = 1;
	const hitMatrixNoShot = '.';
	const hitMatrixMiss = '-';
	const hitMatrixHit = 'X';

	protected $shipMatrix = array();
	protected $hitMatrix = array();

	public function __construct() {
		$this->shipMatrix = $this->emptyMatrix(self::shipMatrixBlank);
		$this->hitMatrix = $this->emptyMatrix(self::hitMatrixNoShot);
	}

	public function getShipMatrix() {
		return $this->shipMatrix;
	}

	public function hitMatrix() {
		return $this->hitMatrix;
	}

	protected function getRowIndex($noteNum) {
		$noteNum = (int) $noteNum;
		if ($noteNum < 1) {
			return 'A';
		}
		($noteNum > self::matrixRowsNum) ? $noteNum = self::matrixRowsNum : false;
		$firstNoteAsciiNum = ord('A');
		$currNoteAsciiNum = $firstNoteAsciiNum + $noteNum;
		$currNote = chr($currNoteAsciiNum);

		return strtoupper(trim($currNote));
	}

	protected function emptyMatrix($defSymbol) {
		$mat = array();
		$lastNote = $this->getRowIndex(self::matrixRowsNum);
		for ($ii = 'A'; $ii <= $lastNote; $ii++) {
			for ($jj = 1; $jj <= self::matrixColsNum; $jj++) {
				$mat[$ii][$jj] = $defSymbol;
			}
		}

		return $mat;
	}

	protected function getShipRandomPosition($shipSize) {
		if (($shipSize <= 0)) {
			return array();
		}
		// Random 1 - Horizontal Axis
		// Random 2 - Vertical Axis
		$axisType = rand(1, 2);
		if ($axisType == 1) {
			$axisXNum = rand(1, self::matrixRowsNum);
			$axisX = $this->getRowIndex($axisXNum);
			$axisY = rand(1, (self::matrixColsNum - $shipSize + 1));
		} else {
			$axisY = rand(1, self::matrixColsNum);
			$axisXNum = rand(1, (self::matrixRowsNum - $shipSize + 1));
			$axisX = $this->getRowIndex($axisXNum);
		}

		return array('axisType' => $axisType, 'axisX' => $axisX, 'axisY' => $axisY);
	}

	public function setShipPosition($shipSize) {
		$shipSize = (int) $shipSize;
		if ($shipSize <= 0 || $shipSize > self::matrixRowsNum || $shipSize > self::matrixColsNum) {
			return false;
		}
		$shipMat = $this->shipMatrix;
		$freePositionsNum = 0;
		$freePositions = array();
		while ($freePositionsNum < $shipSize) {
			if (!$freePositionsNum) {
				$randPos = $this->getShipRandomPosition($shipSize);
				if (!is_array($randPos) || empty($randPos)) {
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
		}

		foreach ($freePositions as $pos) {
			$this->shipMatrix[$pos['axisX']][$pos['axisY']] = self::shipMatrixDeployed;
		}
	}

	public function setPrintMatrix() {
		global $printMatrix;
		$printMatrix = $this->shipMatrix;
	}

	public function loadView() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->shipMatrix;
		$t->render('GameTpl.php');
	}

}

/*
	if ($shipSize > self::matrixRowsNum) {
		$axisNum = rand(1, self::matrixRowsNum);
	}
/**/