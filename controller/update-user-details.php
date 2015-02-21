<?php
if (isset($_POST['register']) || isset($_POST['update'])) {
	$email = $mysqli->real_escape_string($_POST['userEmail']);

	$errorMessage  = validate_form($_POST['userFirstName'], "req", "First Name");
	$errorMessage .= validate_form($_POST['userFirstName'], "name", "First Name");
	$errorMessage .= validate_form($_POST['userLastName'], "req", "Last Name");
	$errorMessage .= validate_form($_POST['userLastName'], "name", "Last Name");
	$errorMessage .= validate_form($_POST['userEmail'], "req", "Email");
	$errorMessage .= validate_form($_POST['userEmail'], "email", "Email");

	$sql = "SELECT * FROM Users
			WHERE Email = '$email'
			".(isset($_SESSION['user_id']) ? "AND User_ID != '".$_SESSION['user_id']."'" : "");
	$result = $mysqli->query($sql);

	$errorMessage .= $result->num_rows ? "Email address is already in use.<br>" : "";

	if (isset($_POST['register']) || strlen($_POST['userPass']) || strlen($_POST['userPass2'])) {
		$errorMessage .= validate_password($_POST['userPass'], $_POST['userPass2']);

		$password = password_hash($_POST['userPass'], PASSWORD_DEFAULT);
	}

	if (isset($_POST['register'])) {
		$captcha = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldk4QETAAAAAHTfv9w-JLqp9FGUvfXeTY-BffAu&response=".$_POST['g-recaptcha-response']), true);

		if (!$captcha['success']) {
			$errorMessage .= "Please verify you're not a robot.<br>";
		}
	}

	if (!strlen($errorMessage)) {
		$datetime = $mysqli->real_escape_string(date("c"));

		$firstName = $mysqli->real_escape_string($_POST['userFirstName']);
		$lastName  = $mysqli->real_escape_string($_POST['userLastName']);

		if (isset($_POST['register'])) {
			$sql = "INSERT INTO Users SET
					FirstName   = '".$firstName."',
					LastName    = '".$lastName."',
					Email       = '".$email."',
					Password    = '".$mysqli->real_escape_string($password)."',
					CreatedDate = '".$datetime."',
					UpdatedDate = '".$datetime."'";
			$mysqli->query($sql);

			$_SESSION['auth'] = 1;
			$_SESSION['email'] = $_POST['userEmail'];
			$_SESSION['user_id'] = $mysqli->insert_id;

			header("Location: account.php");
			exit;
		}
		else {
			$sql = "UPDATE Users SET
					FirstName   = '".$firstName."',
					LastName    = '".$lastName."',
					Email       = '".$email."',
					".(isset($password) ? "Password    = '".$mysqli->real_escape_string($password)."'," : "")."
					UpdatedDate = '".$datetime."'
					WHERE User_ID = '".$_SESSION['user_id']."'";
			$mysqli->query($sql);

			$successMessage = "Your details have been updated.";
		}
	}

}
