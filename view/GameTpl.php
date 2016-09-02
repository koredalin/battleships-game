<?php

namespace view;
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
	<head>
		<title>BattleShips Game</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			p {
				font-family: monospace;
				font-size: 1.2em;
			}
		</style>
	</head>
	<body>
		<div>
			<?php
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
			?>
		</div>
		<div>
			<form name="input" action="index.php" method="post">
				Enter coordinates (row, col), e.g. A5 <input type="input" size="5" name="coord" autocomplete="off" autofocus>
				<input type="submit">
			</form>
		</div>
	</body>
</html>
