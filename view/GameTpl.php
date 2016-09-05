<?php

namespace view;
?>

<!DOCTYPE html>
<html>
	<head>
		<title>BattleShips Game</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			h2 {
				text-align: center;
			}
			.game_row {
				font-family: monospace;
				font-size: 1.2em;
				text-align: center;
			}
			.col_indexes {
				margin-left: 0.48em;
			}
			.print_spaces {
				white-space:pre;
			}
		</style>
	</head>
	<body>
		<div>
			<h2>BattleShips Game</h2>
			<?php
			if (isset($this->vars['status'])) {
				echo '<p class="game_row">' . $this->status . '</p>';
			}

			if (isset($this->vars['printMatrix']) && is_array($this->vars['printMatrix']) && count($this->vars['printMatrix']) > 0) {
				echo '<div class="game_row print_spaces col_indexes">';
				for ($jj = 0; $jj <= 10; $jj++) {
					echo (!$jj) ? '  ' : $jj . ' ';
				}
				echo '</div>';
				foreach ($this->printMatrix as $rowKey => $row) {
					echo '<div class="game_row print_spaces">' . $rowKey . ' ';
					foreach ($row as $colKey => $col) {
						echo $col . ' ';
					}
					echo '</div>';
				}
			}
			?>
		</div>
		<p class="game_row">
			<?php
			if (isset($this->vars['gameSuccess']) && $this->vars['gameSuccess'] && isset($this->vars['hitsCount']) && $this->vars['hitsCount']) {
				echo "Well Done! You completed the game in " . $this->vars['hitsCount'] . " shots.";
			}
			?>
		</p>
		<div>
			<form name="input" action="index.php" method="post">
				<div class="game_row">
					Enter coordinates (row, col), e.g. A5 <input type="input" size="5" name="coord" autocomplete="off" autofocus>
					<input type="submit">
				</div>
			</form>
		</div>
		<p class="game_row"><a href="index.php?new_game=1">Start a New Game</a></p>
	</body>
</html>
