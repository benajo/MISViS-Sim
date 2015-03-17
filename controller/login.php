<?php
if (isset($_POST['login'])) {
	$email = $mysqli->real_escape_string($_POST['userEmail']);
	$datetime = $mysqli->real_escape_string(date("c"));

	$errorMessage = validate_form($_POST['userEmail'], "req", "Email");

	// if user entered an email, check if it exists in the DB
	if (strlen($_POST['userEmail'])) {
		$sql = "SELECT User_ID, Password FROM Users
				WHERE Email = '$email'";
		$result = $mysqli->query($sql);

		if (!$result->num_rows) {
			$errorMessage .= "Email address not found.<br>";
		}
		else {
			// email address found so get the row form the DB
			$row = $result->fetch_assoc();
		}
	}

	if (!strlen($_POST['userPass'])) {
		$errorMessage .= "Password required.";
	}
	// verify enterd password with password in DB
	elseif (isset($row) && !password_verify($_POST['userPass'], $row['Password'])) {
		$errorMessage .= "Incorrect password.";
	}

	// if no errors
	if (!strlen($errorMessage)) {
		$sql = "SELECT User_ID, Password FROM Users
				WHERE Email = '$email'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();

		// set session variables to authorise user access
		$_SESSION['auth'] = 1;
		$_SESSION['email'] = $_POST['userEmail'];
		$_SESSION['user_id'] = $row['User_ID'];

		// create a successful user session entry
		$mysqli->query("INSERT INTO UserSessions SET Email = '$email', Access = 1, Datetime = '$datetime'");

		header("Location: account.php");
		exit;
	}
	else {
		// create an unsuccessful user session entry
		$mysqli->query("INSERT INTO UserSessions SET Email = '$email', Datetime = '$datetime'");
	}

}
