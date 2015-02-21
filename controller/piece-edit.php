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

if (isset($_POST['update_manual'])) {
	$errorMessage  = validate_form($_POST['pieceTitle'], "req", "Title");
	$errorMessage .= validate_form($_POST['pieceData'], "req", "Data");

	if (strlen($_POST['pieceData'])) {
		preg_match_all("/tabstave/i", $_POST['pieceData'], $staves);
		preg_match_all("/tabstave tablature=false notation=true/i", $_POST['pieceData'], $stavesCheck);
		preg_match_all("/(options space)|(tabstave)/i", $_POST['pieceData'], $spaces);

		if (count($staves[0]) != count($stavesCheck[0])) {
			$errorMessage .= "All 'tabstave' must be followed by 'tablature=false notation=true'.<br>";
		}

		for ($i=1; $i < count($spaces[0]); $i+=3) {
			if ($spaces[0][$i] != "tabstave") {
				$errorMessage .= "Each 'tabstave' must be preceeded and followed by a 'options space=x'.<br>";
				break;
			}
		}
	}

	if (!strlen($errorMessage)) {
		$title = $mysqli->real_escape_string(htmlentities($_POST['pieceTitle'], ENT_QUOTES, "UTF-8"));
		$data  = $mysqli->real_escape_string(htmlentities($_POST['pieceData'], ENT_QUOTES, "UTF-8"));

		$sql = "UPDATE Pieces SET
				Title = '$title',
				Data = '$data'
				WHERE Piece_ID = '$pieceId'
				AND   User_ID  = '$userId'";
		$mysqli->query($sql);
	}
}
