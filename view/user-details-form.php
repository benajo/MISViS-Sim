<?php
// if it's the account page, select the user's information for the DB to display on the form
if (PAGE == "account") {
	echo "<h1>Your Details</h1>";

	$sql = "SELECT * FROM Users
			WHERE User_ID = '".$_SESSION['user_id']."'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
}
else {
	echo "<h1>Register</h1>";
}
?>
<form action="<?php echo PAGE; ?>.php" method="post" id="user-details-form">
	<?php if (isset($_POST['register']) || isset($_POST['update'])) { display_messages(); } ?>
	<p>
		<label for="userFirstName">First Name</label>
		<input type="text" name="userFirstName" id="userFirstName"
			   <?php
			   /**
				 * The name and email fields value shows the post value, if it exists,
				 * otherwise the DB result value, if it exists,
				 * otherwise it's empty
				 */
			   ?>
			   value="<?php echo isset($_POST['userFirstName']) ? $_POST['userFirstName'] : (isset($row['FirstName']) ? $row['FirstName'] : ''); ?>">

		<label for="userLastName">Last Name</label>
		<input type="text" name="userLastName" id="userLastName"
			   value="<?php echo isset($_POST['userLastName']) ? $_POST['userLastName'] : (isset($row['LastName']) ? $row['LastName'] : ''); ?>">
	</p>
	<p>
		<label for="userEmail">Email</label>
		<input type="text" name="userEmail" id="userEmail"
			   value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : (isset($row['Email']) ? $row['Email'] : ''); ?>">
	</p>
	<p>
		<label for="userPass"><?php echo PAGE == "account" ? "New " : ""; ?>Password</label>
		<input type="password" name="userPass" id="userPass" value="">

		<label for="userPass2">Confirm Password</label>
		<input type="password" name="userPass2" id="userPass2" value="">
	</p>
	<?php if (PAGE == "register") { ?>
		<div class="g-recaptcha" data-sitekey="6Ldk4QETAAAAAHhjpQwB66HRgvoAI6sfgS16Y02u"></div>
	<?php } ?>
	<p>
	<?php if (PAGE == "register") { ?>
		<input type="submit" name="register" value="Register">
	<?php } else { ?>
		<input type="submit" name="update" value="Update">
	<?php } ?>
	</p>
</form>
