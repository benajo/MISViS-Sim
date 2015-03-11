<h1>New Piece</h1>

<form action="account.php" method="post">
	<?php if (isset($_POST['new_piece'])) { display_messages(); } ?>
	<p>
		<label for="pieceTitle">Title</label>
		<input type="text" name="pieceTitle" id="pieceTitle"
			   value="<?php echo isset($_POST['pieceTitle']) ? $_POST['pieceTitle'] : ''; ?>">
	</p>
	<p>
		<label>Manual piece?</label>
		<?php
		$options = array("Yes" => 1, "No" => 0);

		foreach ($options as $k => $v) {
			$checked = isset($_POST['pieceManual']) && $_POST['pieceManual'] == $v ? " checked " : (!isset($_POST['pieceManual']) && $v == 1 ? " checked " : "");

			echo "<label for='pieceManual{$k}' class='radio'>{$k}</label>";
			echo "<input type='radio' name='pieceManual' id='pieceManual{$k}' value='{$v}' {$checked}>";
		}
		?>
	</p>
	<p>
		<input type="submit" name="new_piece" value="Create">
	</p>
</form>
