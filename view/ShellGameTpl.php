<?php

namespace view;

echo "\r\nBattleShips Game\r\n";
if (isset($this->vars['status'])) {
	echo $this->status . "\r\n";
}

if (isset($this->vars['printMatrix']) && is_array($this->vars['printMatrix']) && count($this->vars['printMatrix']) > 0) {
	for ($jj = 0; $jj <= 10; $jj++) {
		echo $jj . ' ';
	}
	echo "\r\n";

	foreach ($this->printMatrix as $rowKey => $row) {
		echo $rowKey . ' ';
		foreach ($row as $colKey => $col) {
			echo $col . ' ';
		}
		echo "\r\n";
	}
//	print_r($this->vars['printMatrix']);
}

if (isset($this->vars['gameSuccess']) && $this->vars['gameSuccess'] && isset($this->vars['hitsCount']) && $this->vars['hitsCount']) {
	echo "\r\nWell Done! You completed the game in " . $this->vars['hitsCount'] . " shots.";
}