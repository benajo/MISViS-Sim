<?php
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['auth'], $_SESSION['email'], $_SESSION['user_id']);

	header("Location: index.php");
	exit;
}

if (isset($_POST['new_piece'])) {
	$errorMessage = validate_form($_POST['pieceTitle'], "req", "Title");

	if (!strlen($errorMessage)) {
		$datetime = $mysqli->real_escape_string(date("c"));

		$title = $mysqli->real_escape_string(htmlentities($_POST['pieceTitle'], ENT_QUOTES, "UTF-8"));
		$manual = $mysqli->real_escape_string($_POST['pieceManual']);

		$default  = "options space=0\n";
		$default .= "\ttabstave tablature=false notation=true\n";
		$default .= "\tkey=C time=4/4\n\n";
		$default .= "\tnotes :8 \n";
		$default .= "options space=0";

		$sql = "INSERT INTO Pieces SET
				User_ID = '".$_SESSION['user_id']."',
				Title   = '".$title."',
				Manual  = '".$manual."',
				Data    = '".$default."',
				Created = '".$datetime."',
				Updated = '".$datetime."'";
		$result = $mysqli->query($sql);

		if ($result) {
			$pieceId = $mysqli->insert_id;

			$sql = "INSERT INTO PieceStaves SET
					Piece_ID    = '$pieceId',
					Clef_ID     = 1,
					Key_ID      = 1,
					TopSpace    = 10,
					BottomSpace = 10,
					TopTime     = 4,
					BottomTime  = 4
					";
			$result = $mysqli->query($sql);

			header("Location: piece-edit.php?p=".$pieceId);
			exit;
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
elseif (isset($_GET['pieceDelete'])) {
	$pieceId = $mysqli->real_escape_string($_GET['pieceDelete']);

	$sql = "UPDATE Pieces SET
			Deleted = 1
			WHERE Piece_ID = '".$pieceId."'
			AND   User_ID  = '".$_SESSION['user_id']."'";
	$result = $mysqli->query($sql);

	if ($result) {
		$successMessage = "Piece has been deleted.";
	}
	else {
		$errorMessage = "Piece failed to delete, please try again.";
	}

	$deleteMessage = true;
}
