<?php

/**
 * @game BattleShips
 */

namespace model;

use \model\BattleField as BF;
/**
 * @model Ship - abstract class
 * @author Hristo Hristov
 */
abstract class Ship {

	const MAX_ITERATIONS_PER_SHIP_POS_SET = 40;

	/**
	 * @class "\model\BattleField"
	 * alias and object
	 */
	const BF = '\model\BattleField';

	protected $type = '';
	protected $size = 0;
	protected $name = '';
	protected $position = array();

	protected function __construct() {
		$this->size = $this->getShipSize();
	}

	/**
	 * The only thing which is not changed in a ship is its length (size).
	 * Set in Ship constructor.
	 * The successors are obligate to set their size (length).
	 * There is no option for changing their size.
	 */
	abstract protected function getShipSize();

	/**
	 * Magic method __get()
	 * @param type $propName
	 * @return Ship class properties or "false" if theere is no such property.
	 */
	public function __get($propName) {
		$propName = strval(trim($propName));
		if (!in_array($propName, array('size', 'position', 'type', 'name'), true)) {
			return false;
		}

		return $this->$propName;
	}

	/**
	 * Magic method __set()
	 * Sets Ship class a new $this->type, $this->position or $this->name properties.
	 * @param type $propName - String
	 * @param type $propValue - Array, String
	 * Class property $this->size is set on the object construction only.
	 * @return boolean
	 */
	public function __set($propName, $propValue) {
		$propName = strval(trim($propName));
		if (!in_array($propName, array('type', 'position', 'name'), true)) {
			return false;
		}
		$this->$propName = $propValue;

		return $propValue;
	}

	public function setShipPosition(Array &$shipMat, $shipIndex, $shipSize) {
		$shipSize = (int) $shipSize;
		if ($shipSize <= 0 || $shipSize > constant(self::BF . '::MATRIX_ROWS_NUM') || $shipSize > constant(self::BF . '::MATRIX_COLS_NUM')) {
			return false;
		}
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
			$this->position = $freePositions;
			foreach ($freePositions as $pos) {
				$shipMat[$pos['axisX']][$pos['axisY']] = constant(self::BF . '::SHIP_MATRIX_DEPLOYED');
			}
		} else {
			throw new \Exception('Ship with size ' . $shipSize . ' is not positioned.');
		}
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

}
