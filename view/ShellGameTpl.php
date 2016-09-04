<?php

namespace view;

echo "\r\nBattleShips Game\r\n";
if (isset($this->vars['error'])) {
	echo $this->error . "\r\n";
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
			
