<?php

session_start();
include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

$interface = 'web';
$session = array();

if (isset($_SESSION['ships']) && isset($_SESSION['BattleField'])) {
	$session['ships'] = unserialize($_SESSION['ships']);
	$session['BattleField'] = unserialize($_SESSION['BattleField']);
}
$isLoadedGame = isset($session['ships']) && is_array($session['ships']) && count($session['ships']) > 0 && isset($session['BattleField']) && is_object($session['BattleField']);

if (!$isLoadedGame || (isset($_GET['new_game']) && (int) $_GET['new_game'])) {
	session_destroy();
	session_start();
	$game = new controller\ShipPositionsController();
	$game->setShipPositions();
	$game->loadView();
} else if (isset($_POST['coord'])) {
	$game = new controller\HitPositionsController();
	if (strval(trim($_POST['coord'])) === 'ch3At') {
		$game->loadShipsSchema();
	} else if (!$game->setHitPosition($_POST['coord'])) {
		$game->loadHitsSchema('*** Error ***');
	} else {
		$game->hitAPosition();
		$game->loadHitsSchema();
	}
} else {
	$game = new controller\HitPositionsController();
	$game->loadHitsSchema('*** Error ***');
}

if (isset($session['ships']) && isset($session['BattleField'])) {
	$_SESSION['ships'] = serialize($session['ships']);
	$_SESSION['BattleField'] = serialize($session['BattleField']);
}

//print_r($game->getShipMatrix());