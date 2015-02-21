<?php
if (isset($_POST['login'])) {
	$email = $mysqli->real_escape_string($_POST['userEmail']);
	$datetime = $mysqli->real_escape_string(date("c"));

	$errorMessage = validate_form($_POST['userEmail'], "req", "Email");

	if (strlen($_POST['userEmail'])) {
		$sql = "SELECT User_ID, Password FROM Users
				WHERE Email = '$email'";
		$result = $mysqli->query($sql);

		if (!$result->num_rows) {
			$errorMessage .= "Email address not found.";
		}
		else {
			$row = $result->fetch_assoc();
		}
	}

	if (!strlen($_POST['userPass'])) {
		$errorMessage .= "Password required.";
	}
	elseif (isset($row) && !password_verify($_POST['userPass'], $row['Password'])) {
		$errorMessage .= "Incorrect password.";
	}

	if (!strlen($errorMessage)) {
		$sql = "SELECT User_ID, Password FROM Users
				WHERE Email = '$email'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();

		$_SESSION['auth'] = 1;
		$_SESSION['email'] = $_POST['userEmail'];
		$_SESSION['user_id'] = $row['User_ID'];

		$mysqli->query("INSERT INTO UserSessions SET Email = '$email', Access = 1, Datetime = '$datetime'");

		header("Location: account.php");
		exit;
	}
	else {
		$mysqli->query("INSERT INTO UserSessions SET Email = '$email', Datetime = '$datetime'");
	}

}
