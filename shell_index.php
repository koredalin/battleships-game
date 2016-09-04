<?php

session_start();
include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

if (isset($_POST['coord'])) {
	echo 'Post success: ' . strtoupper(trim($_POST['coord']));
}

$interface = 'shell';
define ("FILE_NAME", 'shell_serialization/battleships.txt');
$session = array();
if (file_exists(FILE_NAME)) {
	$session = unserialize(file_get_contents(FILE_NAME));
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

if (isset($session['ships']) && is_array($session['ships']) && isset($session['BattleField']) && is_object($session['BattleField'])) {
	file_put_contents(FILE_NAME, serialize($session));
}