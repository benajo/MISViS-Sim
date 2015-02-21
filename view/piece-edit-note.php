<?php
require '../inc/settings.php';
require '../inc/globals.php';
require '../inc/functions.php';
?>
<fieldset id="pieceStaveNote_NOTE_NO_" class="noteEntry">
	<legend>Note _NOTE_NO_</legend>

	<p>
		<label for="staveNote_STAVE_NO__NOTE_NO_">Note</label>
		<select name="staveNote[_STAVE_NO_][_NOTE_NO_]" id="staveNote_STAVE_NO__NOTE_NO_" class="note">
			<option value="">Select...</option>
			<?php
			$sql = "SELECT * FROM `Notes` ORDER BY Name";
			$result = $mysqli->query($sql);

			while ($row = $result->fetch_assoc()) {
				$s = $row['VexTabNotation'] == "C" ? "selected" : "";

				echo "<option value='".$row['Note_ID']."' data-vex='".$row['VexTabNotation']."' {$s}>".$row['Name']."</option>";
			}
			?>
		</select>
	</p>
	<p>
		<label for="staveNoteDuration_STAVE_NO__NOTE_NO_">Duration</label>
		<select name="staveNoteDuration[_STAVE_NO_][_NOTE_NO_]" id="staveNoteDuration_STAVE_NO__NOTE_NO_" class="duration">
			<option value="">Select...</option>
			<?php
			$sql = "SELECT * FROM `Durations` ORDER BY Name";
			$result = $mysqli->query($sql);

			while ($row = $result->fetch_assoc()) {
				echo "<option value='".$row['Duration_ID']."' data-vex='".$row['VexTabNotation']."'>".$row['Name']."</option>";
			}
			?>
		</select>
	</p>
	<p>
		<label for="staveNoteOctave_STAVE_NO__NOTE_NO_">Octave</label>
		<select name="staveNoteOctave[_STAVE_NO_][_NOTE_NO_]" id="staveNoteOctave_STAVE_NO__NOTE_NO_" class="octave">
			<option value="">Select...</option>
			<?php
			for ($i=0; $i < 11; $i++) {
				echo "<option value='".$i."'>".$i."</option>";
			}
			?>
		</select>
	</p>
	<p>
		<label>Dotted?</label>
		<label for="staveNoteDottedYes_STAVE_NO__NOTE_NO_" class="radio">Yes</label>
		<input type="radio" name="staveNoteDotted[_STAVE_NO_][_NOTE_NO_]" id="staveNoteDottedYes_STAVE_NO__NOTE_NO_" class="dottedYes" value="1">
		<label for="staveNoteDottedNo_STAVE_NO__NOTE_NO_" class="radio">No</label>
		<input type="radio" name="staveNoteDotted[_STAVE_NO_][_NOTE_NO_]" id="staveNoteDottedNo_STAVE_NO__NOTE_NO_" class="dottedNo" value="1" checked>
	</p>
</fieldset>
