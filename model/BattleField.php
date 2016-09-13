<?php
/**
 * @game BattleShips
 */

namespace model;

/**
 * @model BattleField
 * @author Hristo Hristov
 *
 * OOP Principle. All the validations are in the Model.
 */
class BattleField implements BattleFieldInterface {

	private static $instance = NULL;

	const MATRIX_ROWS_NUM = 10;
	const MATRIX_COLS_NUM = 10;
	const SHIP_MATRIX_BLANK = ' ';
	const SHIP_MATRIX_DEPLOYED = 'X';
	const HIT_MATRIX_NO_SHOT = '.';
	const HIT_MATRIX_MISS = '-';
	const HIT_MATRIX_HIT = 'X';

	private $shipMatrix = array();
	private $hitMatrix = array();

	/**
	 * The BattleField is only one.
	 * So we can make only 1 instance of it.
	 * Pattern Singleton.
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new BattleField();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->shipMatrix = $this->emptyMatrix(self::SHIP_MATRIX_BLANK);
		$this->hitMatrix = $this->emptyMatrix(self::HIT_MATRIX_NO_SHOT);
	}
	
	public static function destroy() {
		self::$instance = NULL;
	}

	/**
	 * Validates the row index <1-10>.
	 * Returns row index <A-J>.
	 * @param (int) $noteNum
	 * @return (string) 1 character.
	 * @throws Exception "Not existing row index $noteNum."
	 */
	public static function getRowIndex($noteNum) {
		$noteNum = (int) $noteNum;
		if ($noteNum < 1 || $noteNum > self::MATRIX_ROWS_NUM) {
			throw new Exception('Not existing row index: ' . $noteNum . '.');
		}
		$firstNoteAsciiNum = ord('A');
		$currNoteAsciiNum = $firstNoteAsciiNum + $noteNum - 1;
		$currNote = chr($currNoteAsciiNum);

		return strtoupper(trim($currNote));
	}

	/**
	 * Validates the hit position by coordinate [$axisX]<A-J>, [$axisY]<1-10>.
	 * @param (string) $axisX (1 char), (int) $axisY
	 * @return (int) 0 or 1.
	 */
	public function isValidHitPosition($axisX, $axisY) {
		$axisX = substr(strtoupper(trim($axisX)), 0, 1);
		$axisY = (int) $axisY;

		if (!$axisX || !ctype_alpha($axisX) || ord($axisX) < ord('A') || ord($axisX) > (ord('A') + self::MATRIX_ROWS_NUM - 1) || $axisY < 1 || $axisY > self::MATRIX_COLS_NUM) {
			return 0;
		}
		
		return 1;
	}

	/**
	 * Checks if all the ships are sunk.
	 * @return (int) 0 or 1.
	 */
	public function areAllShipsHit() {
		$shipMatrix = $this->shipMatrix;
		$hitMatrix = $this->hitMatrix;
		foreach ($shipMatrix as $axisX => $row) {
			foreach ($row as $axisY => $value) {
				if ($value === self::SHIP_MATRIX_DEPLOYED && $hitMatrix[$axisX][$axisY] !== self::HIT_MATRIX_HIT) {
					return 0;
				}
			}
		}

		return 1;
	}

	public function hitAPosition(Array &$hitPos) {
		if (!isset($hitPos['axisX']) || !isset($hitPos['axisY']) || !$hitPos['axisX'] || !$hitPos['axisY']) {
			return false;
		}
		if ($this->shipMatrix[$hitPos['axisX']][$hitPos['axisY']] === self::SHIP_MATRIX_DEPLOYED) {
			$this->hitMatrix[$hitPos['axisX']][$hitPos['axisY']] = self::HIT_MATRIX_HIT;
			$hitPos['hit'] = 1;
		} else {
			$this->hitMatrix[$hitPos['axisX']][$hitPos['axisY']] = self::HIT_MATRIX_MISS;
			$hitPos['hit'] = 0;
		}
		
		return true;
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

	/**
	 * Creates an array. Fills it with default parameter.
	 * @param $defSymbol. Type (string).
	 * @return array "empty matrix".
	 */
	private function emptyMatrix($defSymbol) {
		$mat = array();
		$lastNote = $this->getRowIndex(self::MATRIX_ROWS_NUM);
		for ($ii = 'A'; $ii <= $lastNote; $ii++) {
			for ($jj = 1; $jj <= self::MATRIX_COLS_NUM; $jj++) {
				$mat[$ii][$jj] = $defSymbol;
			}
		}

		return $mat;
	}

	/**
	 * Validates the current BattleField Ships or Hits Matrix.
	 * Checks for Rows and Columns number.
	 */
	private function isValidMatrix(Array $matrix) {
		if (empty($matrix) || count($matrix) != self::MATRIX_ROWS_NUM) {
			return 0;
		}
		foreach ($matrix as $row) {
			if (count($row) != self::MATRIX_COLS_NUM) {
				return 0;
			}
		}

		return 1;
	}

}
