<?php

/**
 * @game BattleShips
 */

namespace controller;

use \model\GameStatus as GS;

/**
 * @controller HitPositionsController
 *
 * @author Hristo Hristov
 */
class HitPositionsController {

	/**
	 * @class "\model\BattleField"
	 * alias and object
	 */
	const BF = '\model\BattleField';

	private $bField = null;
	private $hitPosition = array('axisX' => '', 'axisY' => 0, 'hit' => 0);

	public function __construct() {
		global $session;
		if (!isset($session['BattleField']) || !is_object($session['BattleField'])) {
			throw new Exception('No BattleField Object Loaded in the Session.');
		}
		$this->bField = $session['BattleField'];
		GS::$gameSuccess = (isset($session['gameSuccess'])) ? (int) $session['gameSuccess'] : 0;
		GS::$hitsCount = (isset($session['hitsCount'])) ? (int) $session['hitsCount'] : 0;
	}

	/**
	 * @checks whether or not the new coordinate is valid.
	 * @sets $this->hitPosition.
	 */
	public function setHitPosition($pos) {
		$pos = strtoupper(trim($pos));
		$axisX = substr($pos, 0, 1);
		$axisY = (int) substr($pos, 1);
		if (!$this->bField->isValidHitPosition($axisX, $axisY)) {
			return false;
		}
		$this->hitPosition = array('axisX' => $axisX, 'axisY' => $axisY);

		return true;
	}

	/**
	 * @checks whether or not $this->hitPosition has values.
	 * @hits a position. Sets the field status $this->bField->setHitMatrix($hitMatrix).
	 * @loads a Hits View with "Miss" or "Sunk" status.
	 */
	public function hitAPosition() {
		if (!$this->bField->hitAPosition($this->hitPosition)) {
			$this->loadHitsSchema('*** Miss ***');
			return false;
		}
		$this->setBattleFieldToSession();
		GS::$gameSuccess = $this->bField->areAllShipsHit();
		if ($this->hitPosition['hit']) {
			$this->loadHitsSchema('*** Sunk ***');
		} else {
			$this->loadHitsSchema('*** Miss ***');
		}

		return true;
	}

	/**
	 * @counts the total hits per the game.
	 * @stops counting after all ships are hit.
	 */
	public function addAHit() {
		if (!isset(GS::$hitsCount)) {
			GS::$hitsCount = 0;
		}
		if (!(int) GS::$gameSuccess) {
			GS::$hitsCount++;
		}
	}

	/**
	 * @loads Ships Schema in the view.
	 * @creates $t - a view object.
	 */
	public function loadShipsSchema() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->bField->getShipMatrix();
		$tpl = $this->getTemplate();
		$t->render($tpl);
	}

	/**
	 * @loads Hits Schema in the view.
	 * @creates $t - a view object.
	 * @sets $t->printMatrix
			$t->gameSuccess
			$t->hitsCount.
	 */
	public function loadHitsSchema($status = '') {
		$t = new \vendor\ViewRender();
		($status) ? $t->status = strval(trim($status)) : false;
		$t->printMatrix = $this->bField->getHitMatrix();
		$t->gameSuccess = (int) GS::$gameSuccess;
		$t->hitsCount = (int) GS::$hitsCount;
		$tpl = $this->getTemplate();
		$t->render($tpl);
	}
	
	/**
	 * Returns game template.
	 * @return string
	 */
	protected function getTemplate() {
		global $interface;
		if ($interface === 'web') {
			return 'GameTpl.php';
		}
		if ($interface === 'shell_commands') {
			return 'ShellCommandsGameTpl.php';
		}
		
		return 'ShellGameTpl.php';
	}

	private function setBattleFieldToSession() {
		global $session;
		$session['BattleField'] = $this->bField;
	}

}
