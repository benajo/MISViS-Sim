<?php
require '../inc/settings.php';
require '../inc/globals.php';
require '../inc/functions.php';
?>
<fieldset id="pieceStaveNote_NOTE_NO_" class="noteEntry">
	<legend>Note _NOTE_NO_</legend>

	<p><button type="button" onclick="pieceDeleteNote(_STAVE_NO_, _NOTE_NO_)">Delete</button></p>

	<p>
		<label for="staveNote_STAVE_NO__NOTE_NO_">Note</label>
		<select name="staveNote[_STAVE_NO_][]" id="staveNote_STAVE_NO__NOTE_NO_" class="note">
			<option value="">Select...</option>
			<?php
			$sql = "SELECT * FROM `Notes` ORDER BY Name";
			$result = $mysqli->query($sql);

			while ($row = $result->fetch_assoc()) {
				$s = $row['VexTabNotation'] == "C" ? "selected" : "";

				echo "<option value='".$row['Note_ID']."' data-vex='".$row['VexTabNotation']."' data-non-note='".$row['NonNote']."' {$s}>".$row['Name']."</option>";
			}
			?>
		</select>
	</p>
	<p>
		<label for="staveNoteDuration_STAVE_NO__NOTE_NO_">Duration</label>
		<select name="staveNoteDuration[_STAVE_NO_][]" id="staveNoteDuration_STAVE_NO__NOTE_NO_" class="duration">
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
		<select name="staveNoteOctave[_STAVE_NO_][]" id="staveNoteOctave_STAVE_NO__NOTE_NO_" class="octave">
			<option value="">Select...</option>
			<?php
			for ($i=0; $i < 11; $i++) {
				echo "<option value='".$i."'>".$i."</option>";
			}
			?>
		</select>
	</p>
	<p>
		<label for="staveNoteDotted_STAVE_NO__NOTE_NO_">Dotted?</label>
		<input type="checkbox" name="staveNoteDotted[_STAVE_NO_][]" id="staveNoteDotted_STAVE_NO__NOTE_NO_" class="dotted" value="1">
	</p>
</fieldset>
