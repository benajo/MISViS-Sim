<?php
$pieceId = $mysqli->real_escape_string($_GET['p']);

$sql = "SELECT * FROM Pieces
		WHERE Piece_ID = '$pieceId'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
?>

<h1><?php echo $row['Title']; ?></h1>

<div id="vextabContainer" class="vex-tabdiv" width="833" scale="1.2" editor="true" editor_width="994" editor_height="300"><?php echo $row['Data']; ?></div>

<p>
	<button type="button" class="simButton" onclick="startFizzing()">Fizzing</button>
	<button type="button" class="simButton" onclick="startWhirlpool()">Whirlpool</button>
	<button type="button" onclick="stopAll()">Off</button>
</p>

<script type="text/javascript">
$(function() {
	// ensure the VexTab textarea is hidden from the page
	$("#vextabContainer textarea").hide();
});
</script>
