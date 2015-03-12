<h1>Reset your password</h1>

<form action="reset-password.php<?php echo $validRequest ? "?c=".$_GET['c']."&amp;i=".$_GET['i'] : ""; ?>" method="post" id="login-form">
	<?php display_messages(); ?>

	<?php
	// $validRequest is set in the controller/reset-password.php
	if ($validRequest) {
		?>
		<p>
			<label for="userPass">New Password</label>
			<input type="password" name="userPass" id="userPass" value="">
		</p>
		<p>
			<label for="userPass2">Confirm Password</label>
			<input type="password" name="userPass2" id="userPass2" value="">
		</p>
		<p>
			<input type="submit" name="step2" value="Submit">
			<a href="reset-password.php">Back</a>
		</p>
		<?php
	}
	else {
		?>
		<p>
			<label for="userEmail">Email</label>
			<input type="text" name="userEmail" id="userEmail"
				   value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>">
		</p>
		<p>
			<input type="submit" name="step1" value="Submit">
			<a href="login.php">Back to login</a>
		</p>
		<?php
	}
	?>
</form>
