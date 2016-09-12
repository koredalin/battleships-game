<?php
/**
 * @game BattleShips
 */

namespace view;

echo "\r\n\r\nBattleShips Game\r\n";
if (isset($this->vars['status'])) {
	echo $this->status . "\r\n";
}

if (isset($this->vars['printMatrix']) && is_array($this->vars['printMatrix']) && count($this->vars['printMatrix']) > 0) {
	for ($jj = 0; $jj <= 10; $jj++) {
		echo ($jj == 0) ? '  ' : $jj . ' ';
	}
	echo "\r\n";

	foreach ($this->printMatrix as $rowKey => $row) {
		echo $rowKey . ' ';
		foreach ($row as $colKey => $col) {
			echo $col . ' ';
		}
		echo "\r\n";
	}
}
echo "\r\n";

if (isset($this->vars['gameSuccess']) && $this->vars['gameSuccess'] && isset($this->vars['hitsCount']) && $this->vars['hitsCount']) {
	echo "Well Done! You completed the game in " . $this->vars['hitsCount'] . " shots.\r\n";
}

echo "Hit a position:\r\n";
echo "curl --data \"coord=h4\" http://localhost/battleships/shell_commands_index.php\r\n";
echo "\r\n";
//echo "See all ships positions:\r\n";
//echo "curl --data \"coord=show\" http://localhost/battleships/shell_commands_index.php\r\n";
//echo "\r\n";
echo "Start a new game:\r\n";
echo "curl http://localhost/battleships/shell_commands_index.php?new_game=1\r\n";