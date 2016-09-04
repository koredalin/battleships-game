<?php

namespace controller;

class HitPositionsController {

	/**
	 * @class "\model\BattleField"
	 * alias and object
	 */
	const BF = '\model\BattleField';

	protected $bField = null;

	/**
	 * @class "\model\Ship" and inheritors
	 * array of objects
	 */
	protected $ships = array();
	protected $hitPosition = array('axisX' => '', 'axisY' => 0);

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

		if (!$axisX || !ctype_alpha($axisX) || ord($axisX) < ord('A') || ord($axisX) > (ord('A') + constant(self::BF . '::matrixRowsNum') - 1) || $axisY < 1 || $axisY > constant(self::BF . '::matrixColsNum')) {
//			var_dump('False Index - axisX: ' . $axisX . ', axisY: ' . $axisY . '.');
			return false;
		}
		$this->hitPosition = array('axisX' => $axisX, 'axisY' => $axisY);

		return true;
	}

	public function loadShipsSchema() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->bField->getShipMatrix();
		global $interface;
		$tpl = ($interface === 'web') ? 'GameTpl.php' : 'ShellGameTpl.php';
		$t->render($tpl);
	}

	public function loadHitsSchema($error = '') {
		$t = new \vendor\ViewRender();
		($error) ? $t->error = strval(trim($error)) : false;
		$t->printMatrix = $this->bField->getHitMatrix();
		global $interface;
		$tpl = ($interface === 'web') ? 'GameTpl.php' : 'ShellGameTpl.php';
		$t->render($tpl);
	}

	public function hitAPosition() {
		$hitPos = $this->hitPosition;
		if (!$hitPos['axisX'] || !$hitPos['axisY']) {
			return false;
		}
		$shipMatrix = $this->bField->getShipMatrix();
		$hitMatrix = $this->bField->getHitMatrix();
		if ($shipMatrix[$hitPos['axisX']][$hitPos['axisY']] === constant(self::BF . '::shipMatrixDeployed')) {
			$hitMatrix[$hitPos['axisX']][$hitPos['axisY']] = constant(self::BF . '::hitMatrixHit');
		} else {
			$hitMatrix[$hitPos['axisX']][$hitPos['axisY']] = constant(self::BF . '::hitMatrixMiss');
		}
		$this->bField->setHitMatrix($hitMatrix);
		$this->setBattleFieldToSession();
		
		return true;
	}

	public function setBattleFieldToSession() {
		global $session;
		$session['BattleField'] = $this->bField;
	}

}
