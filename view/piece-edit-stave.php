<?php
/**
 * This file is only called by an ajax call when the piece-edit.php
 * page is loaded. _STAVE_NO_ is a place holder for
 * JavaScript to update appropriately.
 */

require '../inc/settings.php';
require '../inc/globals.php';
require '../inc/functions.php';
?>
<fieldset id="pieceStave_STAVE_NO_" class="stave">
	<legend>Stave <span class="staveNo"></span></legend>

	<p><button type="button" onclick="pieceDeleteStave(_STAVE_NO_)">Delete</button>

	<p>
		<label for="staveClef_STAVE_NO_">Clef</label>
		<select name="staveClef[_STAVE_NO_]" id="staveClef_STAVE_NO_" class="clef">
			<?php
			$sql = "SELECT * FROM Clefs ORDER BY Name";
			$result = $mysqli->query($sql);

			while ($row = $result->fetch_assoc()) {
				$s = $row['VexTabNotation'] == "treble" ? "selected" : "";

				echo "<option value='".$row['Clef_ID']."' data-vex='".$row['VexTabNotation']."' {$s}>".$row['Name']."</option>";
			}
			?>
		</select>

		<label for="staveKey_STAVE_NO_">Key</label>
		<select name="staveKey[_STAVE_NO_]" id="staveKey_STAVE_NO_" class="key">
			<?php
			$sql = "SELECT * FROM `Keys` ORDER BY Name";
			$result = $mysqli->query($sql);

			while ($row = $result->fetch_assoc()) {
				$s = $row['VexTabNotation'] == "C" ? "selected" : "";

				echo "<option value='".$row['Key_ID']."' data-vex='".$row['VexTabNotation']."' {$s}>".$row['Name']."</option>";
			}
			?>
		</select>

		<label for="topSpace_STAVE_NO_">Top Space</label>
		<input type="text" name="topSpace[_STAVE_NO_]" id="topSpace_STAVE_NO_" class="topSpace"
			   value="10">

		<label for="bottomSpace_STAVE_NO_">Bottom Space</label>
		<input type="text" name="bottomSpace[_STAVE_NO_]" id="bottomSpace_STAVE_NO_" class="bottomSpace"
			   value="10">

		<label for="upperTimeSig_STAVE_NO_">Upper Time Sig</label>
		<input type="text" name="upperTimeSig[_STAVE_NO_]" id="upperTimeSig_STAVE_NO_" class="topTime"
			   value="4">

		<label for="lowerTimeSig_STAVE_NO_">Lower Time Sig</label>
		<input type="text" name="lowerTimeSig[_STAVE_NO_]" id="lowerTimeSig_STAVE_NO_" class="bottomTime"
			   value="4">
	</p>

	<div class="notes">

	</div>
	<p class="newNote">
		<button type="button" onclick="pieceNewNote(_STAVE_NO_)">New Note</button>
	</p>
</fieldset>
