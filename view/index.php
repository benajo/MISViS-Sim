<h1>Visual Stress Simulations on Music</h1>

<p>This web application was created to simulate Meares-Irlen syndrome/visual stress (MISViS) on musical notation. MISViS is a “disorder characterized by visual perceptual distortions and/or somatic issues, including but not limited to: blurriness, transpositions, letter reversals, shimmer, glare and eye-strain or headache”. There are other applications that can simulate visual stress symptoms on text (DyslexSim) and images (VisionSimulations.com).</p>

<p>If you are new, please visit the <a href="register.php">register</a> page to create an account to start creating music and viewing the simulations.</p>

<h1>Sample Simulation</h1>

<p>
	<button type="button" class="simButton" onclick="startFizzing()">Fizzing</button>
	<button type="button" class="simButton" onclick="startWhirlpool()">Whirlpool</button>
	<button type="button" onclick="stopAll()">Off</button>
</p>

<div id="vextabContainer" class="vex-tabdiv" width="833" scale="1.2" editor="true" editor_width="994" editor_height="300">
options space=-25
	tabstave tablature=false notation=true
	key=Am time=6/8

	notes :8 E-D#/5 | E-D#-E/5 B/4 Dn-C/5 | :4 A/4 :8 ## C-E-A/4 | :4 B/4 :8 ## E-G#-B/4 | :4 C/5 :8 ## E/4-E-D#/5
options space=0

options space=0
	tabstave tablature=false notation=true
	key=Am time=6/8

	notes :8 E-D#-E/5 B/4 Dn-C/5 | :4 A/4 :8 ## C-E-A/4 | :4 B/4 :8 ## E/4-C/5-B/4 | :2 A/4 :8 E-D#/5 | E-D#-E/5 B/4 Dn-C/5
options space=0

options space=0
	tabstave tablature=false notation=true
	key=Am time=6/8

	notes :4 A/4 :8 ## C-E-A/4 | :4 B/4 :8 ## E-G#-B/4 | :4 C/5 :8 ## E/4-E/5-D#/5 | E-D#-E/5 B/4 Dn-C/5 | :4 A/4 :8 ## C-E-A/4
options space=0

options space=0
	tabstave tablature=false notation=true
	key=Am time=6/8

	notes :4 B/4 :8 ## E/4-C/5-B/4 | :4 A/4 :8 ## B/4-C-D/5 | :4d E/5 :8 G/4-F-E/5 | :4d D/5 :8 F/4-E-D/5 | :4d C/5 :8 E/4-D-C/5
options space=0
</div>

<script type="text/javascript">
$(function() {
	// ensure the VexTab textarea is hidden from the page
	$("#vextabContainer textarea").hide();
});
</script>