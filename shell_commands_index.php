<?php
/**
 * @game BattleShips
 * @author Hristo Hristov
 */

include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

/**
 * GET Request
 * curl http://localhost/battleships/shell_commands_index.php?new_game=1
 * POST Request
 * curl --data "coord=h4" http://localhost/battleships/shell_commands_index.php
 */
$interface = 'shell_commands';
define("FILE_NAME", 'shell_serialization/battleships_commands.txt');
$session = array();
if (file_exists(FILE_NAME)) {
	$session = unserialize(file_get_contents(FILE_NAME));
}
$isLoadedGame = is_array($session) && isset($session['ships']) && is_array($session['ships']) && count($session['ships']) > 0 && isset($session['BattleField']) && is_object($session['BattleField']);
/**
 * Session Duration
 * If the user last change is before more than 20 minutes, the game restarts.
 */
$sessionDuration = 20 * 60;
$isValidSession = array_key_exists('lastChange', $session) && ((int) $session['lastChange'] >= (int) (strtotime(date('Y-m-d H:i:s')) - $sessionDuration));

if (!$isLoadedGame || (isset($_GET['new_game']) && (int) $_GET['new_game']) || !$isValidSession) {
	$session = array();
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

if (is_array($session) && isset($session['ships']) && is_array($session['ships']) && isset($session['BattleField']) && is_object($session['BattleField'])) {
	$session['gameSuccess'] = \model\GameStatus::$gameSuccess;
	$session['hitsCount'] = \model\GameStatus::$hitsCount;
	$session['lastChange'] = (int) strtotime(date('Y-m-d H:i:s'));
	file_put_contents(FILE_NAME, serialize($session));
}