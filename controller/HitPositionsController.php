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
		if (!isset($_SESSION['BattleField']) || !is_object($_SESSION['BattleField'])) {
			throw new Exception('No BattleField Object Loaded in the Session.');
		}
		$this->bField = $_SESSION['BattleField'];
	}

	public function setHitPosition($pos) {
		$pos = strtoupper(trim($pos));
		$axisX = substr($pos, 0, 1);
		$axisY = (int) substr($pos, 1);

		var_dump(__LINE__);
		var_dump(ord('A'));
		var_dump($axisX);
		var_dump(ord($axisX));
		if (!$axisX || !ctype_alpha($axisX) || ord($axisX) < ord('A') || ord($axisX) > (ord('A') + constant(self::BF . '::matrixRowsNum') - 1)) { // || $axisY < 1 || $axisY > constant(self::BF . '::matrixColsNum')) {
			var_dump('False Letter');
			return false;
		}
		$this->hitPosition = array('axisX' => $axisX, 'axisY' => $axisY);

		return true;
	}

	public function loadShipsSchema() {
		$t = new \vendor\ViewRender();
		$t->printMatrix = $this->bField->getShipMatrix();
		$t->render('GameTpl.php');
	}

	public function loadHitsSchema($error = '') {
		$t = new \vendor\ViewRender();
		($error) ? $t->error = strval(trim($error)) : false;
		$t->printMatrix = $this->bField->getHitMatrix();
		$t->render('GameTpl.php');
	}

	public function hitPosition() {
		if (!$this->hitPosition['axisX'] || !$this->hitPosition['axisY']) {
			return false;
		}
		$shipMatrix = $this->bField->getShipMatrix();
		$hitMatrix = $this->bField->getHitMatrix();
		if ($shipMatrix[$this->hitPosition['axisX']][$this->hitPosition['axisY']] === constant(self::BF . '::shipMatrixDeployed')) {
			$hitMatrix[$this->hitPosition['axisX']][$this->hitPosition['axisY']] = constant(self::BF . '::hitMatrixHit');
		} else {
			$hitMatrix[$this->hitPosition['axisX']][$this->hitPosition['axisY']] = constant(self::BF . '::hitMatrixMiss');
		}
		$this->bField->setHitMatrix($hitMatrix);
		$this->setBattleFieldToSession();
		
		return true;
	}

	public function setBattleFieldToSession() {
		$_SESSION['BattleField'] = $this->bField;
	}

}
