<?php
/**
 * @game BattleShips
 */

namespace controller;

/**
 * HitPositionsController
 *
 * @author Hristo Hristov
 */
class HitPositionsController implements HitPositionsInterface {

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
	}

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

	public function hitAPosition() {
		$hitPos = $this->hitPosition;
		if (!$hitPos['axisX'] || !$hitPos['axisY']) {
			$this->loadHitsSchema('*** Miss ***');
			return false;
		}
		$shipMatrix = $this->bField->getShipMatrix();
		$hitMatrix = $this->bField->getHitMatrix();
		if ($shipMatrix[$hitPos['axisX']][$hitPos['axisY']] === constant(self::BF . '::SHIP_MATRIX_DEPLOYED')) {
			$hitMatrix[$hitPos['axisX']][$hitPos['axisY']] = constant(self::BF . '::HIT_MATRIX_HIT');
			$this->hitPosition['hit'] = 1;
		} else {
			$hitMatrix[$hitPos['axisX']][$hitPos['axisY']] = constant(self::BF . '::HIT_MATRIX_MISS');
			$this->hitPosition['hit'] = 0;
		}
		$this->bField->setHitMatrix($hitMatrix);
		$this->setBattleFieldToSession();
		global $session;
		$session['gameSuccess'] = $this->bField->areAllShipsHit();
		if ($this->hitPosition['hit']) {
			$this->loadHitsSchema('*** Sunk ***');
		} else {
			$this->loadHitsSchema('*** Miss ***');
		}

		return true;
	}

	public function addAHit() {
		global $session;
		if (!isset($session['hitsCount']) || !$session['hitsCount']) {
			$session['hitsCount'] = 0;
		}
		if (!isset($session['gameSuccess']) || !$session['gameSuccess']) {
			$session['hitsCount'] ++;
		}
	}

	public function loadShipsSchema() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->bField->getShipMatrix();
		global $interface;
		$tpl = ($interface === 'web') ? 'GameTpl.php' : 'ShellGameTpl.php';
		$t->render($tpl);
	}

	public function loadHitsSchema($status = '') {
		$t = new \vendor\ViewRender();
		($status) ? $t->status = strval(trim($status)) : false;
		$t->printMatrix = $this->bField->getHitMatrix();
		global $interface;
		$tpl = ($interface === 'web') ? 'GameTpl.php' : 'ShellGameTpl.php';
		global $session;
		$t->gameSuccess = (isset($session['gameSuccess']) && $session['gameSuccess']) ? $session['gameSuccess'] : 0;
		$t->hitsCount = (isset($session['hitsCount']) && $session['hitsCount']) ? $session['hitsCount'] : 0;
		$t->render($tpl);
	}

	private function setBattleFieldToSession() {
		global $session;
		$session['BattleField'] = $this->bField;
	}

}
