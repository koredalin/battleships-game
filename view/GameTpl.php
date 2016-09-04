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
			p {
				font-family: monospace;
				font-size: 1.2em;
				text-align: center;
			}
		</style>
	</head>
	<body>
		<div>
			<h2>BattleShips Game</h2>
			<?php
			if (isset($this->vars['error'])) {
				echo '<p>' . $this->error . '</p>';
			}
			
			if (isset($this->vars['printMatrix']) && is_array($this->vars['printMatrix']) && count($this->vars['printMatrix']) > 0) {
				echo '<p>';
				for ($jj = 0; $jj <= 10; $jj++) {
					echo $jj . ' ';
				}
				echo '</p>';
				foreach ($this->printMatrix as $rowKey => $row) {
					echo '<p>' . $rowKey . ' ';
					foreach ($row as $colKey => $col) {
						echo $col . ' ';
					}
					echo '</p>';
				}
			}
			?>
		</div>
		<div>
			<form name="input" action="index.php" method="post">
				<p>
					Enter coordinates (row, col), e.g. A5 <input type="input" size="5" name="coord" autocomplete="off" autofocus>
					<input type="submit">
				</p>
			</form>
		</div>
		<p><a href="index.php?new_game=1">Start a New Game</a></p>
	</body>
</html>
