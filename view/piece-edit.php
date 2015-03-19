<?php
/**
 * This files lists the options for editing a piece.
 *
 * If it is a manual piece, then simply the Vextab textarea is displayed for editing.
 *
 * If it is a non-manual piece, all of the current staves and notes are extracted from
 * the DB to be displayed.
 */
?>
<h1>Edit Piece</h1>

<?php
$pieceId = $mysqli->real_escape_string($_GET['p']);

$sql = "SELECT * FROM Pieces
		WHERE Piece_ID = '$pieceId'";
$result = $mysqli->query($sql);
$piece = $result->fetch_assoc();
?>

<form action="piece-edit.php?p=<?php echo $_GET['p']; ?>" method="post" id="pieceEdit">
	<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
	<input type="hidden" name="manual" value="<?php echo $piece['Manual']; ?>">

	<?php display_messages(); ?>
	<p>
		<label for="pieceTitle">Title</label>
		<input type="text" name="pieceTitle" id="pieceTitle"
			   value="<?php echo isset($_POST['pieceTitle']) ? $_POST['pieceTitle'] : $piece['Title']; ?>">
	</p>

	<div id="vextabContainer" class="vex-tabdiv" width="833" scale="1.2" editor="true" editor_width="994" editor_height="300"><?php echo isset($_POST['pieceData']) ? $_POST['pieceData'] : $piece['Data']; ?></div>

	<?php if (!$piece['Manual']) { ?>
		<div id="staveList">
			<?php
			$sql = "SELECT * FROM PieceStaves
					WHERE Piece_ID = '$pieceId'";
			$staves = $mysqli->query($sql);

			if ($staves->num_rows) {
				$staveCount = 1;

				while ($stave = $staves->fetch_assoc()) {
					?>
					<fieldset id="pieceStave<?php echo $staveCount; ?>" class="stave">
						<legend>Stave <span class="staveNo"><?php echo $staveCount; ?></span></legend>

						<p><button type="button" onclick="pieceDeleteStave(<?php echo $staveCount; ?>)">Delete</button>

						<p>
							<label for="staveClef<?php echo $staveCount; ?>">Clef</label>
							<select name="staveClef[<?php echo $staveCount; ?>]" id="staveClef<?php echo $staveCount; ?>" class="clef">
								<?php
								$sql = "SELECT * FROM Clefs ORDER BY Name";
								$result = $mysqli->query($sql);

								while ($row = $result->fetch_assoc()) {
									$s = $stave['Clef_ID'] == $row['Clef_ID'] ? "selected" : "";

									echo "<option value='".$row['Clef_ID']."' data-vex='".$row['VexTabNotation']."' {$s}>".$row['Name']."</option>";
								}
								?>
							</select>

							<label for="staveKey<?php echo $staveCount; ?>">Key</label>
							<select name="staveKey[<?php echo $staveCount; ?>]" id="staveKey<?php echo $staveCount; ?>" class="key">
								<?php
								$sql = "SELECT * FROM `Keys` ORDER BY Name";
								$result = $mysqli->query($sql);

								while ($row = $result->fetch_assoc()) {
									$s = $stave['Key_ID'] == $row['Key_ID'] ? "selected" : "";

									echo "<option value='".$row['Key_ID']."' data-vex='".$row['VexTabNotation']."' {$s}>".$row['Name']."</option>";
								}
								?>
							</select>

							<label for="upperTimeSig<?php echo $staveCount; ?>">Upper Time Sig</label>
							<input type="text" name="upperTimeSig[<?php echo $staveCount; ?>]" id="upperTimeSig<?php echo $staveCount; ?>" class="topTime"
								   value="<?php echo $stave['TopTime']; ?>">

							<label for="lowerTimeSig<?php echo $staveCount; ?>">Lower Time Sig</label>
							<input type="text" name="lowerTimeSig[<?php echo $staveCount; ?>]" id="lowerTimeSig<?php echo $staveCount; ?>" class="bottomTime"
								   value="<?php echo $stave['BottomTime']; ?>">
						</p>

						<p>
							<label for="topSpace<?php echo $staveCount; ?>">Top Space</label>
							<input type="text" name="topSpace[<?php echo $staveCount; ?>]" id="topSpace<?php echo $staveCount; ?>" class="topSpace"
								   value="<?php echo $stave['TopSpace']; ?>">

							<label for="bottomSpace<?php echo $staveCount; ?>">Bottom Space</label>
							<input type="text" name="bottomSpace[<?php echo $staveCount; ?>]" id="bottomSpace<?php echo $staveCount; ?>" class="bottomSpace"
								   value="<?php echo $stave['BottomSpace']; ?>">
						</p>

						<div class="notes">
							<?php
							$sql = "SELECT * FROM PieceStaveNotes
									WHERE PieceStave_ID = '".$stave['PieceStave_ID']."'";
							$notes = $mysqli->query($sql);

							if ($notes->num_rows) {
								$noteCount = 1;

								while ($note = $notes->fetch_assoc()) {
									?>
									<fieldset id="pieceStaveNote<?php echo $noteCount; ?>" class="noteEntry">
										<legend>Note <?php echo $noteCount; ?></legend>

										<p><button type="button" onclick="pieceDeleteNote(<?php echo $staveCount; ?>, <?php echo $noteCount; ?>)">Delete</button>

										<p>
											<label for="staveNote<?php echo $staveCount.$noteCount; ?>">Note</label>
											<select name="staveNote[<?php echo $staveCount; ?>][]" id="staveNote<?php echo $staveCount.$noteCount; ?>" class="note">
												<option value="">Select...</option>
												<?php
												$sql = "SELECT * FROM `Notes` ORDER BY Name";
												$result = $mysqli->query($sql);

												while ($row = $result->fetch_assoc()) {
													$s = $note['Note_ID'] == $row['Note_ID'] ? "selected" : "";

													echo "<option value='".$row['Note_ID']."' data-vex='".$row['VexTabNotation']."' data-non-note='".$row['NonNote']."' {$s}>".$row['Name']."</option>";
												}
												?>
											</select>
										</p>
										<p>
											<label for="staveNoteDuration<?php echo $staveCount.$noteCount; ?>">Duration</label>
											<select name="staveNoteDuration[<?php echo $staveCount; ?>][]" id="staveNoteDuration<?php echo $staveCount.$noteCount; ?>" class="duration">
												<option value="">Select...</option>
												<?php
												$sql = "SELECT * FROM `Durations` ORDER BY Name";
												$result = $mysqli->query($sql);

												while ($row = $result->fetch_assoc()) {
													$s = $note['Duration_ID'] == $row['Duration_ID'] ? "selected" : "";

													echo "<option value='".$row['Duration_ID']."' data-vex='".$row['VexTabNotation']."' {$s}>".$row['Name']."</option>";
												}
												?>
											</select>
										</p>
										<p>
											<label for="staveNoteOctave<?php echo $staveCount.$noteCount; ?>">Octave</label>
											<select name="staveNoteOctave[<?php echo $staveCount; ?>][]" id="staveNoteOctave<?php echo $staveCount.$noteCount; ?>" class="octave">
												<option value="">Select...</option>
												<?php
												for ($i=0; $i < 11; $i++) {
													$s = $note['Octave'] === (string)$i ? "selected" : "";

													echo "<option value='".$i."' {$s}>".$i."</option>";
												}
												?>
											</select>
										</p>
										<p>
											<label for="staveNoteDotted<?php echo $staveCount.$noteCount; ?>">Dotted?</label>
											<input type="checkbox" name="staveNoteDotted[<?php echo $staveCount; ?>][]" id="staveNoteDotted<?php echo $staveCount.$noteCount; ?>" class="dotted" value="1" <?php echo $note['Dotted'] ? "checked" : ""; ?>>
										</p>
									</fieldset>
									<?php
									$noteCount++;
								}
							}
							?>
						</div>
						<p class="newNote">
							<button type="button" onclick="pieceNewNote(<?php echo $staveCount; ?>)">New Note</button>
						</p>
					</fieldset>
					<?php
					$staveCount++;
				}
			}
			?>
		</div>
		<p>
			<button type="button" onclick="pieceNewStave()">New Stave</button>
		</p>

		<p><input type="submit" name="update" value="Update"></p>

		<script type="text/javascript">
		$(function() {
			// ensure the textarea is hidden for non-manual editing
			$("#vextabContainer textarea").hide();
			// update the vextab canvas on page load
			updateVexTabTextarea();
		});
		</script>
	<?php } else { ?>
		<p><input type="submit" name="update_manual" value="Update"></p>
	<?php } ?>
</form>
