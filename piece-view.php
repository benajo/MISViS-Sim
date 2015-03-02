<?php
require './inc/settings.php';
require './inc/globals.php';
require './inc/functions.php';
require './inc/auth.php';
require './controller/piece-edit.php';
require './view/header.php';
?>

<?php
$sql = "SELECT * FROM Pieces
		WHERE Piece_ID = '$pieceId'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
?>

<h1><?php echo $row['Title']; ?></h1>

<div id="vextabContainer" class="vex-tabdiv" width="833" scale="1.2" editor="true" editor_width="994" editor_height="300"><?php echo isset($_POST['pieceData']) ? $_POST['pieceData'] : $row['Data']; ?></div>

<p>
	<button type="button" onclick="fizzing()">Fizzing</button>
	<button type="button" onclick="fizzingOff()">Off</button>
</p>

<script type="text/javascript">
$(function() {
	$("#vextabContainer textarea").hide();
});
</script>

<?php
require './view/footer.php';
?>
