<?php

session_start();
include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

if (isset($_POST['coord'])) {
	echo 'Post success: ' . strtoupper(trim($_POST['coord']));
}

$interface = 'shell';
$fileData = array();
if (file_exists('shell_serialization/battleships.txt')) {
	$fileData = file_get_contents('shell_serialization/battleships.txt');
}
$session = array();
if (isset($fileData['ships']) && isset($fileData['BattleField'])) {
	$session['ships'] = unserialize($fileData['ships']);
	$session['BattleField'] = unserialize($fileData['BattleField']);
}
$isLoadedGame = isset($session['ships']) && is_array($session['ships']) && count($session['ships']) > 0 && isset($session['BattleField']) && is_object($session['BattleField']);
var_dump($isLoadedGame);
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
//print_r($session['BattleField']); exit;
if (isset($session['ships']) && isset($session['BattleField'])) {
	$fileData['ships'] = serialize($session['ships']);
	$fileData['BattleField'] = serialize($session['BattleField']);
	file_put_contents('shell_serialization/battleships.txt', $fileData);
}

//print_r($game->getShipMatrix());