<?php

namespace model;

class BattleField {

	protected static $instance = NULL;

	const matrixRowsNum = 10;
	const matrixColsNum = 10;
	const shipMatrixBlank = 0;
	const shipMatrixDeployed = 1;
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
		// TODO - Is valid matrix check.
		return $this->shipMatrix = $matrix;
	}

	public function setHitMatrix(Array $matrix) {
		// TODO - Is valid matrix check.
		return $this->hitMatrix = $matrix;
	}

}
