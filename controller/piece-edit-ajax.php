<?php
require_once '../inc/settings.php';
require_once '../inc/globals.php';
require_once '../inc/functions.php';
require_once '../inc/auth.php';

$pieceId = $mysqli->real_escape_string($_POST['p']);
$userId = $mysqli->real_escape_string($_SESSION['user_id']);

$mysqli->query("DELETE FROM PieceStaves WHERE Piece_ID = '$pieceId'");
// $mysqli->query("DELETE FROM PieceStaveNotes WHERE Piece_ID = '$pieceId'");

// print_r($_POST);

if ($_POST['manual'] == "1") {
	$json['errorMessage']  = validate_form($_POST['pieceTitle'], "req", "Title");
	$json['errorMessage'] .= validate_form($_POST['pieceData'], "req", "Data");

	if (strlen($_POST['pieceData'])) {
		preg_match_all("/tabstave/i", $_POST['pieceData'], $staves);
		preg_match_all("/tabstave tablature=false notation=true/i", $_POST['pieceData'], $stavesCheck);
		preg_match_all("/(options space)|(tabstave)/i", $_POST['pieceData'], $spaces);

		if (count($staves[0]) != count($stavesCheck[0])) {
			$json['errorMessage'] .= "All 'tabstave' must be immediately followed by 'tablature=false notation=true'.<br>";
		}

		for ($i=1; $i < count($spaces[0]); $i+=3) {
			if ($spaces[0][$i] != "tabstave") {
				$json['errorMessage'] .= "Each 'tabstave' must be preceded and followed by a 'options space=x'.<br>";
				break;
			}
		}
	}

	if (!strlen($json['errorMessage'])) {
		$datetime = $mysqli->real_escape_string(date("c"));

		$title = $mysqli->real_escape_string(htmlentities($_POST['pieceTitle'], ENT_QUOTES, "UTF-8"));
		$data  = $mysqli->real_escape_string(htmlentities($_POST['pieceData'], ENT_QUOTES, "UTF-8"));

		$sql = "UPDATE Pieces SET
				Title   = '$title',
				Data    = '$data',
				Updated = '$datetime'
				WHERE Piece_ID = '$pieceId'
				AND   User_ID  = '$userId'";
		$mysqli->query($sql);

		$json['successMessage'] = "The piece has been updated successfully.";
	}
}
else {
	for ($i=0; $i<count($_POST['staveClef']); $i++) {
		$sql = "INSERT INTO PieceStaves SET
				Piece_ID    = '$pieceId',
				Clef_ID     = '".$mysqli->real_escape_string($_POST['staveClef'][$i])."',
				Key_ID      = '".$mysqli->real_escape_string($_POST['staveKey'][$i])."',
				TopSpace    = '".$mysqli->real_escape_string($_POST['topSpace'][$i])."',
				BottomSpace = '".$mysqli->real_escape_string($_POST['bottomSpace'][$i])."',
				TopTime     = '".$mysqli->real_escape_string($_POST['upperTimeSig'][$i])."',
				BottomTime  = '".$mysqli->real_escape_string($_POST['lowerTimeSig'][$i])."'
				";
		$result = $mysqli->query($sql);

		$newId = $mysqli->insert_id;

		for ($j=0; $j<count($_POST['staveNote'][$i]); $j++) {
			$duration = strlen($_POST['staveNoteDuration'][$i][$j]) ? "'".$mysqli->real_escape_string($_POST['staveNoteDuration'][$i][$j])."'" : "NULL";
			$octave = strlen($_POST['staveNoteOctave'][$i][$j]) ? "'".$mysqli->real_escape_string($_POST['staveNoteOctave'][$i][$j])."'" : "NULL";
			$dotted = isset($_POST['staveNoteDotted'][$i][$j]) && $_POST['staveNoteDotted'][$i][$j] == 1 ? 1 : 0;

			$sql = "INSERT INTO PieceStaveNotes SET
					PieceStave_ID = '$newId',
					Note_ID       = '".$mysqli->real_escape_string($_POST['staveNote'][$i][$j])."',
					Duration_ID   = $duration,
					Octave        = $octave,
					Dotted        = $dotted
					";
			$result = $mysqli->query($sql);
		}

		$datetime = $mysqli->real_escape_string(date("c"));

		$title = $mysqli->real_escape_string(htmlentities($_POST['pieceTitle'], ENT_QUOTES, "UTF-8"));
		$data  = $mysqli->real_escape_string(htmlentities($_POST['pieceData'], ENT_QUOTES, "UTF-8"));

		$sql = "UPDATE Pieces SET
				Title   = '$title',
				Data    = '$data',
				Updated = '$datetime'
				WHERE Piece_ID = '$pieceId'
				AND   User_ID  = '$userId'";
		$mysqli->query($sql);
	}

	$json['successMessage'] = "The piece has been updated successfully.";
}

echo json_encode($json);
