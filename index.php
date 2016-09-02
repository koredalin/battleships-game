<?php
include_once 'vendor' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

$game = new controller\GameController();

$game->setShipPosition(4);
$game->setShipPosition(4);
$game->setShipPosition(5);

$game->loadView();
//print_r($game->getShipMatrix());