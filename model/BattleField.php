<?php

namespace model;

class BattleField {

	protected static $instance = NULL;

	const matrixRowsNum = 10;
	const matrixColsNum = 10;
	const shipMatrixBlank = ' ';
	const shipMatrixDeployed = 'X';
	const hitMatrixNoShot = '.';
	const hitMatrixMiss = '-';
	const hitMatrixHit = 'X';

	protected $shipMatrix = array();
	protected $hitMatrix = array();

	protected function __construct() {
		$this->shipMatrix = $this->emptyMatrix(self::shipMatrixBlank);
		$this->hitMatrix = $this->emptyMatrix(self::hitMatrixNoShot);
	}

	/**
	 * The BattleField is only one.
	 * So we can make only 1 instance of it.
	 * Pattern Singleton
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new BattleField();
		}

		return self::$instance;
	}

	protected function emptyMatrix($defSymbol) {
		$mat = array();
		$lastNote = $this->getRowIndex(\model\BattleField::matrixRowsNum);
		for ($ii = 'A'; $ii <= $lastNote; $ii++) {
			for ($jj = 1; $jj <= self::matrixColsNum; $jj++) {
				$mat[$ii][$jj] = $defSymbol;
			}
		}

		return $mat;
	}

	public static function getRowIndex($noteNum) {
		$noteNum = (int) $noteNum;
		if ($noteNum < 1 || $noteNum > self::matrixRowsNum) {
			throw new Exception('Not existing row index ' . $noteNum . '.');
		}
		$firstNoteAsciiNum = ord('A');
		$currNoteAsciiNum = $firstNoteAsciiNum + $noteNum - 1;
		$currNote = chr($currNoteAsciiNum);

		return strtoupper(trim($currNote));
	}

	public function getShipMatrix() {
		return $this->shipMatrix;
	}

	public function getHitMatrix() {
		return $this->hitMatrix;
	}

	public function setShipMatrix(Array $matrix) {
		if ($this->isValidMatrix($matrix)) {
			return $this->shipMatrix = $matrix;
		}

		throw new \Exception('Not supported Ships Matrix.');
	}

	public function setHitMatrix(Array $matrix) {
		if ($this->isValidMatrix($matrix)) {
			return $this->hitMatrix = $matrix;
		}

		throw new \Exception('Not supported Hits Matrix.');
	}

	protected function isValidMatrix(Array $matrix) {
		if (empty($matrix) || count($matrix) != self::matrixRowsNum) {
			return false;
		}
		foreach ($matrix as $row) {
			if (count($row) != self::matrixColsNum) {
				return false;
			}
		}

		return true;
	}

}
