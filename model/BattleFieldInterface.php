<?php

namespace model;

/**
 *
 * @author Hristo
 */
interface BattleFieldInterface {
	
	/**
	 * The BattleField is only one.
	 * So we can make only 1 instance of it.
	 * Pattern Singleton
	 */
	public static function getInstance();

	public static function getRowIndex($noteNum);

	public function areAllShipsHit();

	public function getShipMatrix();

	public function getHitMatrix();

	public function setShipMatrix(Array $matrix);

	public function setHitMatrix(Array $matrix);
	
}
