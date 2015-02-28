<?php
require './inc/settings.php';
require './inc/globals.php';
require './inc/functions.php';
require './inc/auth.php';
require './controller/account.php';
require './controller/update-user-details.php';
require './view/header.php';
?>
<h1>Your Details</h1>
<?php
require './view/user-details-form.php';
?>

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

<h1>Pieces</h1>
<?php
$sql = "SELECT * FROM Pieces
		WHERE User_ID = '".$_SESSION['user_id']."'
		AND   Deleted = 0";
$result = $mysqli->query($sql);

if ($result->num_rows) {
	?>
	<?php if (isset($deleteMessage)) { display_messages(); } ?>
	<table id="piecesTable">
		<tr>
			<th>Title</th>
			<th>Manual</th>
			<th>Created</th>
			<th>&nbsp;</th>
		</tr>
		<?php
		while ($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td>".$row['Title']."</td>";
			echo "<td>".($row['Manual'] ? "Yes" : "No")."</td>";
			echo "<td>".$row['Created']."</td>";
			echo "<td>";
				echo "<button type='button' onclick=\"window.location='piece-edit.php?p=".$row['Piece_ID']."'\">Edit</button> ";
				echo "<button type='button' onclick=\"window.location='account.php?pieceDelete=".$row['Piece_ID']."'\">Delete</button>";
			echo "</td>";
			echo "</tr>";
		}
		?>
	</table>
	<?php
}
else {
	echo "You have not created any pieces yet.";
}
?>

<?php
require './view/footer.php';
?>
