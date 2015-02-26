<?php
$validPiece = false;

if (isset($_GET['p'])) {
	$pieceId = $mysqli->real_escape_string($_GET['p']);
	$userId = $mysqli->real_escape_string($_SESSION['user_id']);

	$sql = "SELECT Piece_ID FROM Pieces
			WHERE Piece_ID = '$pieceId'
			AND   User_ID  = '$userId'";
	$result = $mysqli->query($sql);

	if ($result->num_rows) {
		$validPiece = true;
	}
}

if (!$validPiece) {
	header("Location: account.php");
	exit;
}
