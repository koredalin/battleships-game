<?php

session_start();
include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

if (isset($_SESSION['ships']) && isset($_SESSION['BattleField'])) {
	$_SESSION['ships'] = unserialize($_SESSION['ships']);
	$_SESSION['BattleField'] = unserialize($_SESSION['BattleField']);
}
$isLoadedGame = isset($_SESSION['ships']) && is_array($_SESSION['ships']) && count($_SESSION['ships']) > 0 && isset($_SESSION['BattleField']) && is_object($_SESSION['BattleField']);

		var_dump(__LINE__);
if (!$isLoadedGame || (isset($_GET['new_game']) && (int) $_GET['new_game'])) {
		var_dump(__LINE__);
	session_destroy();
	session_start();
	$game = new controller\ShipPositionsController();
	$game->action();
	$game->loadView();
} else if (isset($_POST['coord'])) {
		var_dump(__LINE__);
	$game = new controller\HitPositionsController();
	if (strval(trim($_POST['coord'])) === 'ch3At') {
		var_dump(__LINE__);
		$game->loadShipsSchema();
	} else if (!$game->setHitPosition($_POST['coord'])) {
		var_dump(__LINE__);
		$game->loadHitsSchema('*** Error ***');
	} else {
		var_dump(__LINE__);
		$game->hitPosition();
		$game->loadHitsSchema();
	}
}

if (isset($_SESSION['ships']) && isset($_SESSION['BattleField'])) {
	$_SESSION['ships'] = serialize($_SESSION['ships']);
	$_SESSION['BattleField'] = serialize($_SESSION['BattleField']);
}

//print_r($game->getShipMatrix());