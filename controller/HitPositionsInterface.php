<?php
/**
 * @game BattleShips
 */

namespace controller;

/**
 * HitPositionsInterface
 *
 * @author Hristo Hristov
 */
interface HitPositionsInterface {
	
	/**
	 * @checks whether or not the new coordinate is valid.
	 * @sets $this->hitPosition.
	 */
	public function setHitPosition($pos);
	
	/**
	 * @checks whether or not $this->hitPosition has values.
	 * @hits a position. Sets the field status $this->bField->setHitMatrix($hitMatrix).
	 * @loads a Hits View with "Miss" or "Sunk" status.
	 */
	public function hitAPosition();
	
	/**
	 * @counts the total hits per the game.
	 * @stops counting after all ships are hit.
	 */
	public function addAHit();
	
	/**
	 * @loads Ships Schema in the view.
	 * @creates $t - a view object.
	 */
	public function loadShipsSchema();
	
	/**
	 * @loads Hits Schema in the view.
	 * @creates $t - a view object.
	 * @sets $t->printMatrix
			$t->gameSuccess
			$t->hitsCount.
	 */
	public function loadHitsSchema($status = '');
}
