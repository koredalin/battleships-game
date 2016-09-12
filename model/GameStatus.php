<?php
/**
 * @game BattleShips
 */

namespace model;

/**
 * @model GameStatus
 * @author Hristo Hristov
 *
 * The class holds Battleships game main statistics.
 */
class GameStatus {
	/**
	 * Is the game finished?
	 * @var type (int) 0 or 1
	 */
	public static $gameSuccess = 0;
	/**
	 * Number of hits until the game finish?
	 * @var type (int)
	 */
	public static $hitsCount = 0;
}
