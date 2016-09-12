<?php
/**
 * @game BattleShips
 * @author Hristo Hristov
 */

session_start();
include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

$interface = 'web';
$session = array();

if (isset($_SESSION['session'])) {
	$session = unserialize($_SESSION['session']);
}
$isLoadedGame = isset($session['ships']) && is_array($session['ships']) && count($session['ships']) > 0 && isset($session['BattleField']) && is_object($session['BattleField']);

if (!$isLoadedGame || (isset($_GET['new_game']) && (int) $_GET['new_game'])) {
	session_destroy();
	session_start();
	$game = new controller\ShipPositionsController();
	$game->setShipPositions();
} else if (isset($_POST['coord'])) {
	$game = new controller\HitPositionsController();
	$game->addAHit();
	if (strval(trim($_POST['coord'])) === 'show') {
		$game->loadShipsSchema();
	} else if (!$game->setHitPosition($_POST['coord'])) {
		$game->loadHitsSchema('*** Error ***');
	} else {
		$game->hitAPosition();
	}
} else {
	$game = new controller\HitPositionsController();
	$game->addAHit();
	$game->loadHitsSchema('*** Error ***');
}

if (isset($session) && is_array($session) && count($session) > 0) {
	$session['gameSuccess'] = \model\GameStatus::$gameSuccess;
	$session['hitsCount'] = \model\GameStatus::$hitsCount;
	$_SESSION['session'] = serialize($session);
}