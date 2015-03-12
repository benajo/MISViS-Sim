<?php
$validPiece = false; // when false will redirect user away form page

if (isset($_GET['p'])) {
	$pieceId = $mysqli->real_escape_string($_GET['p']);
	$userId = $mysqli->real_escape_string($_SESSION['user_id']);

	// check piece id is valid and assigned to the logged in user
	$sql = "SELECT Piece_ID FROM Pieces
			WHERE Piece_ID = '$pieceId'
			AND   User_ID  = '$userId'";
	$result = $mysqli->query($sql);

	if ($result->num_rows == 1) {
		// because the piece exists, set valid to true
		$validPiece = true;
	}
}

// redirect user back to account page if the piece id was invalid
if (!$validPiece) {
	header("Location: account.php");
	exit;
}
