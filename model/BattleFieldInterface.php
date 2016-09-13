<?php
/**
 * @game BattleShips
 */

namespace model;

/**
 * BattleFieldInterface
 *
 * @author Hristo Hristov
 */
interface BattleFieldInterface {
	
	/**
	 * The BattleField is only one.
	 * So we can make only 1 instance of it.
	 * Pattern Singleton.
	 */
	public static function getInstance();
	
	/**
	 * Destroys the BattleField instance.
	 */
	public static function destroy();

    /**
     * Returns a letter indicating axisX key.
     * @param (int) $noteNum. The letter's position.
     * @return string, one charecter, uppercase.
	 * @throws an exception if $noteNum is not in the current BattleField available rows set.
     */
	public static function getRowIndex($noteNum);

    /**
	 * Indicates if the new hit position is a valid BattleField position.
     * @params (string) $axisX (1 char), (int) $axisY - coordinates.
     * @return 0 or 1.
     */
	public function isValidHitPosition($axisX, $axisY);
	
    /**
	 * Indicates if all BattleField ships are hit.
     * @param No Params.
     * @return 0 or 1.
     */
	public function areAllShipsHit();

    /**
     * @return $this->shipMatrix
     */
	public function getShipMatrix();

    /**
     * @return $this->hitMatrix
     */
	public function getHitMatrix();

    /**
     * @sets $this->shipMatrix
	 * @throws an exception if the new matrix is not suported.
     */
	public function setShipMatrix(Array $matrix);

    /**
     * @sets $this->hitMatrix
	 * @throws an exception if the new matrix is not suported.
     */
	public function setHitMatrix(Array $matrix);
	
	/**
	 * Hit a position from $this->hitMatrix.
	 * @return false on invalid $hitPos (position).
     * @sets a $this->hitMatrix cell with self::HIT_MATRIX_HIT or self::HIT_MATRIX_MISS.
	 * @sets the parameter &$hitPos by reference.
     */
	public function hitAPosition(Array &$hitPos);
	
}
