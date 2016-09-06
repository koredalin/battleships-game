<?php

namespace controller;

/**
 *
 * @author Hristo
 */
interface HitPositionsInterface {
	
	public function setHitPosition($pos);
	
	public function hitAPosition();
	
	public function addAHit();
	
	public function loadShipsSchema();
	
	public function loadHitsSchema($status = '');
}
