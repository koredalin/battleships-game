<?php

/**
 * @game BattleShips
 * @author Hristo Hristov
 */
include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

$interface = 'shell';
define("FILE_NAME", 'shell_serialization/battleships.txt');

$line = 'new';

do {

	$session = array();
	if (file_exists(FILE_NAME)) {
		$session = unserialize(file_get_contents(FILE_NAME));
	}
	$isLoadedGame = is_array($session) && isset($session['ships']) && is_array($session['ships']) && count($session['ships']) > 0 && isset($session['BattleField']) && is_object($session['BattleField']);

	if (!$isLoadedGame || $line === 'new') {
		$session = array();
		$game = new controller\ShipPositionsController();
		$game->setShipPositions();
	} else if (strlen($line) > 0) {
		$game = new controller\HitPositionsController();
		$game->addAHit();
		if ($line === 'show') {
			$game->loadShipsSchema();
		} else if (!$game->setHitPosition($line)) {
			$game->loadHitsSchema('*** Error ***');
		} else {
			$game->hitAPosition();
		}
	} else {
		$game = new controller\HitPositionsController();
		$game->addAHit();
		$game->loadHitsSchema('*** Error ***');
	}

	if (is_array($session) && isset($session['ships']) && is_array($session['ships']) && isset($session['BattleField']) && is_object($session['BattleField'])) {
		$session['gameSuccess'] = \model\GameStatus::$gameSuccess;
		$session['hitsCount'] = \model\GameStatus::$hitsCount;
		file_put_contents(FILE_NAME, serialize($session));
	}
	unset($game);
	unset($session);
	
	fwrite(STDOUT, "Enter coordinates (row, col), e.g. B7: ");
	$line = strval(trim(fgets(STDIN)));
	
} while ($line !== 'exit');
