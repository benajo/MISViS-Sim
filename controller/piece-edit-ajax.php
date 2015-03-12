<?php
require_once '../inc/settings.php';
require_once '../inc/globals.php';
require_once '../inc/functions.php';
require_once '../inc/auth.php';

$pieceId = $mysqli->real_escape_string($_POST['p']);
$userId = $mysqli->real_escape_string($_SESSION['user_id']);

// select all piece staves for the piece being updated
$sql = "SELECT * FROM PieceStaves
		WHERE Piece_ID = '$pieceId'";
$result = $mysqli->query($sql);

if ($result->num_rows) {
	// loop through the stave pieces and delete each one
	// then delete piece stave notes for each piece stave
	// each stave and note will be created again later in this script
	while ($row = $result->fetch_assoc()) {
		$mysqli->query("DELETE FROM PieceStaves WHERE Piece_ID = '$pieceId'");

		$mysqli->query("DELETE FROM PieceStaveNotes WHERE PieceStave_ID = '".$row['PieceStave_ID']."'");
	}
}

// manual update
if ($_POST['manual'] == "1") {
	$json['errorMessage']  = validate_form($_POST['pieceTitle'], "req", "Title");
	$json['errorMessage'] .= validate_form($_POST['pieceData'], "req", "Data");

	if (strlen($_POST['pieceData'])) {
		// match all of the tabstaves
		preg_match_all("/tabstave/i", $_POST['pieceData'], $staves);
		// tabstaves must be followed by 'tablature=false notation=true'
		preg_match_all("/tabstave tablature=false notation=true/i", $_POST['pieceData'], $stavesCheck);
		// find all 'options space' and 'tabstave'
		preg_match_all("/(options space)|(tabstave)/i", $_POST['pieceData'], $spaces);

		// ensures all 'tabstave' is followed by 'tablature=false notation=true'
		if (count($staves[0]) != count($stavesCheck[0])) {
			$json['errorMessage'] .= "All 'tabstave' must be immediately followed by 'tablature=false notation=true'.<br>";
		}

		/**
		 * Order should be:
		 *    1. options space
		 *    2. tabstave
		 *    3. options space
		 *    4. options space
		 *    5. tabstave
		 *    6. options space
		 *    7. options space
		 *    8. ...repeat...
		 *
		 * So just check the 2nd element, 4th, 7th etc to ensure they are all 'tabstave'
		 */
		for ($i=1; $i < count($spaces[0]); $i+=3) {
			if ($spaces[0][$i] != "tabstave") {
				$json['errorMessage'] .= "Each 'tabstave' must be preceded and followed by a 'options space=x'.<br>";
				break;
			}
		}
	}

	// if no errors, update the piece
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
// non-manual piece editing
else {
	// for ($i=0; $i<count($_POST['staveClef']); $i++) {
	// do not use a for loop like above, because the key may not start at 0
	// and also may not increase in increments of 1
	foreach ($_POST['staveClef'] as $i => $v) {
		// create the piece staves
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

		// for ($j=0; $j<count($_POST['staveNote'][$i]); $j++) {
		// do not use a for loop like above
		foreach ($_POST['staveNote'][$i] as $j => $v) {
			// duration and octave can be null
			if (strlen($_POST['staveNoteDuration'][$i][$j]))
				$duration = "'".$mysqli->real_escape_string($_POST['staveNoteDuration'][$i][$j])."'";
			else
				$duration = "NULL";

			if (strlen($_POST['staveNoteOctave'][$i][$j]))
				$octave = "'".$mysqli->real_escape_string($_POST['staveNoteOctave'][$i][$j])."'";
			else
				$octave = "NULL";

			// dotted will not be set if it wasn't even ticked
			$dotted = isset($_POST['staveNoteDotted'][$i][$j]) && $_POST['staveNoteDotted'][$i][$j] == 1 ? 1 : 0;

			// create the piece stave notes
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

		// javascript creates the data string and posts it along too
		$data  = $mysqli->real_escape_string(htmlentities($_POST['pieceData'], ENT_QUOTES, "UTF-8"));

		// update the piece
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

// create json object for javascript to read after the ajax call finishes
echo json_encode($json);
