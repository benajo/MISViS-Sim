<h1>Pieces</h1>

<?php
// select all of a user's non-deleted pieces to be listed
$sql = "SELECT * FROM Pieces
		WHERE User_ID = '".$_SESSION['user_id']."'
		AND   Deleted = 0";
$result = $mysqli->query($sql);

if ($result->num_rows) {
	if (isset($deleteMessage)) { display_messages(); }
	?>
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
				echo "<button type='button' onclick=\"window.location='piece-view.php?p=".$row['Piece_ID']."'\">View</button> ";
				echo "<button type='button' onclick=\"window.location='piece-edit.php?p=".$row['Piece_ID']."'\">Edit</button> ";
				echo "<button type='button' onclick=\"if (confirm('Click OK to delete this piece.')) window.location='account.php?pieceDelete=".$row['Piece_ID']."'\">Delete</button>";
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
