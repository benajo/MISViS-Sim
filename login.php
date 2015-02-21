<?php
require './inc/settings.php';
require './inc/globals.php';
require './inc/functions.php';
require './controller/login.php';
require './view/header.php';
?>
<h1>Login</h1>

<form action="login.php" method="post" id="login-form">
	<?php display_messages(); ?>
	<p>
		<label for="userEmail">Email</label>
		<input type="text" name="userEmail" id="userEmail"
			   value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>">
	</p>
	<p>
		<label for="userPass">Password</label>
		<input type="password" name="userPass" id="userPass" value="">
	</p>
	<p>
		<input type="submit" name="login" value="Login">
		<a href="reset-password.php">Forgotten your password?</a>
	</p>
</form>

<?php
require './view/footer.php';
?>
