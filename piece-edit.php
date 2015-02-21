<?php
require './inc/settings.php';
require './inc/globals.php';
require './inc/functions.php';
require './inc/auth.php';
require './controller/piece-edit.php';
require './view/header.php';
?>

<h1>Edit Piece</h1>

<?php
$sql = "SELECT * FROM Pieces
		WHERE Piece_ID = '$pieceId'";
$result = $mysqli->query($sql);
$piece = $result->fetch_assoc();
?>

<form action="piece-edit.php?p=<?php echo $_GET['p']; ?>" method="post" id="pieceEdit">
	<?php display_messages(); ?>
	<p>
		<label for="pieceTitle">Title</label>
		<input type="text" name="pieceTitle" id="pieceTitle"
			   value="<?php echo isset($_POST['pieceTitle']) ? $_POST['pieceTitle'] : $piece['Title']; ?>">
	</p>

	<div id="vextabContainer" class="vex-tabdiv" width="833" scale="1.2" editor="true" editor_width="994" editor_height="300"><?php echo isset($_POST['pieceData']) ? $_POST['pieceData'] : $piece['Data']; ?></div>

	<?php if (!$piece['Manual']) { ?>
		<div id="staveList">
			<!-- <legend>Stave 1</legend>

			<p>
				<label for="staveClef1">Clef</label>
				<select name="staveClef[1]" id="staveClef1">
					<?php
					$sql = "SELECT * FROM Clefs ORDER BY Name";
					$result = $mysqli->query($sql);

					while ($row = $result->fetch_assoc()) {
						if ($_POST) {
							$s = "selected";
						}
						elseif (!$_POST && $piece) {
							$s = "selected";
						}
						elseif (!$_POST && !$piece && $row['VexTabNotation'] == "treble") {
							$s = "selected";
						}

						echo "<option value='".$row['Clef_ID']."' {$s}>".$row['Name']."</option>";
					}
					?>
				</select>

				<label for="staveKey1">Key</label>
				<select name="staveKey[1]" id="staveKey1">
					<?php
					$sql = "SELECT * FROM `Keys` ORDER BY Name";
					$result = $mysqli->query($sql);

					while ($row = $result->fetch_assoc()) {
						echo "<option value='".$row['Key_ID']."'>".$row['Name']."</option>";
					}
					?>
				</select>

				<label for="topSpace1">Top Space</label>
				<input type="text" name="topSpace[1]" id="topSpace1"
					   value="">

				<label for="bottomSpace1">Bottom Space</label>
				<input type="text" name="bottomSpace[1]" id="bottomSpace1"
					   value="">

				<label for="upperTimeSig1">Upper Time Sig</label>
				<input type="text" name="upperTimeSig[1]" id="upperTimeSig1"
					   value="">

				<label for="lowerTimeSig1">Lower Time Sig</label>
				<input type="text" name="lowerTimeSig[1]" id="lowerTimeSig1"
					   value="">
			</p>

			<fieldset>
				<legend>Note 1</legend>

				<p>
					<label for="staveNote11">Note</label>
					<select name="staveNote[1][1]" id="staveNote11">
						<option value="">Select...</option>
						<?php
						$sql = "SELECT * FROM `Notes` ORDER BY Name";
						$result = $mysqli->query($sql);

						while ($row = $result->fetch_assoc()) {
							echo "<option value='".$row['Note_ID']."'>".$row['Name']."</option>";
						}
						?>
					</select>

					<label for="staveNoteDuration11">Duration</label>
					<select name="staveNoteDuration[1][1]" id="staveNoteDuration11">
						<?php
						$sql = "SELECT * FROM `Durations` ORDER BY Name";
						$result = $mysqli->query($sql);

						while ($row = $result->fetch_assoc()) {
							echo "<option value='".$row['Duration_ID']."'>".$row['Name']."</option>";
						}
						?>
					</select>

					<label for="staveNoteOctave11">Octave</label>
					<select name="staveNoteOctave[1][1]" id="staveNoteOctave11">
						<?php
						for ($i=0; $i < 11; $i++) {
							echo "<option value='".$i."'>".$i."</option>";
						}
						?>
					</select>

					<label>Dotted?</label>
					<label for="staveNoteDottedYes11" class="radio">Yes</label>
					<input type="radio" name="staveNoteDotted[1][1]" id="staveNoteDottedYes11" value="1">
					<label for="staveNoteDottedNo11" class="radio">No</label>
					<input type="radio" name="staveNoteDotted[1][1]" id="staveNoteDottedNo11" value="1">
				</p>
			</fieldset>
			<p>
				<button onclick="">New Note</button>
			</p> -->
		</div>
		<script type="text/javascript">
		$(function() {
			// $("#vextabContainer textarea").hide();
		});
		</script>
	<?php } ?>
	<p>
		<button type="button" onclick="pieceNewStave()">New Stave</button>
	</p>
	<p>
		<input type="submit" name="update_manual" value="Update">
	</p>
</form>

<script type="text/javascript">
$(function() {
	$("#vextabContainer textarea").attr("name", "pieceData");
});
</script>

<?php
require './view/footer.php';
?>
