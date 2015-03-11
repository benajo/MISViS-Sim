<?php
$sql = "SELECT * FROM Pieces
		WHERE Piece_ID = '$pieceId'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
?>

<h1><?php echo $row['Title']; ?></h1>

<div id="vextabContainer" class="vex-tabdiv" width="833" scale="1.2" editor="true" editor_width="994" editor_height="300"><?php echo isset($_POST['pieceData']) ? $_POST['pieceData'] : $row['Data']; ?></div>

<p>
	<button type="button" class="simButton" onclick="startFizzing()">Fizzing</button>
</p>
<p>
	<button type="button" class="simButton" onclick="startWhirlpool()">Whirlpool</button>
</p>
<p>
	<button type="button" onclick="stopAll()">Off</button>
</p>

<script type="text/javascript">
$(function() {
	$("#vextabContainer textarea").hide();
});
</script>