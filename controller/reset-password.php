<?php
$validRequest = false; // when true will show the second step for entering a new password

if (isset($_GET['c']) || isset($_GET['i'])) {
	$code = $mysqli->real_escape_string($_GET['c']);
	$userId = $mysqli->real_escape_string($_GET['i']);

	$sql = "SELECT User_ID FROM Users
			WHERE ResetCode = '$code'
			AND   User_ID = '$userId'";
	$result = $mysqli->query($sql);

	// the code and user id are valid
	if ($result->num_rows) {
		$validRequest = true;
	}
	else {
		$errorMessage = "Reset code is invalid.";
	}
}

// this step checks if the email entered is in the DB then sends the user an email
if (isset($_POST['step1'])) {
	$email = $mysqli->real_escape_string($_POST['userEmail']);

	// get user's name and id for the email
	$sql = "SELECT User_ID, FirstName, LastName FROM Users
			WHERE Email = '$email'";
	$result = $mysqli->query($sql);

	if ($result->num_rows) {
		$row = $result->fetch_assoc();

		// create a new code for this request
		$code = random_code(32);

		$message  = "Dear ".$row['FirstName']." ".$row['LastName'].",\n\n";
		$message .= "Please copy and paste the following link into your browser to reset your password:\n\n";
		$message .= $_SERVER['HTTP_HOST']."/reset-password.php?c=$code&i=".$row['User_ID'];

		// create PHPMailer class object
		$mail = new PHPMailer;
		// $mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = 'misvissim@gmail.com';
		$mail->Password = 'jlM7T&i9Im%84ZMZ3x$vA2g@5jSd!eEF';

		$mail->setFrom('reset@misvissim.com');
		$mail->addReplyTo('noreply@misvissim.com');

		$mail->addAddress($_POST['userEmail']);

		$mail->Subject = "MISViS Sim :: Password reset";
		$mail->Body    = $message;

		// update the user's code
		$sql = "UPDATE Users SET ResetCode = '$code'
				WHERE User_ID = '".$row['User_ID']."'";
		$result = $mysqli->query($sql);

		if ($result && $mail->send()) {
			$successMessage = "Request was successful. Please check your inbox.";
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
	else {
		$errorMessage = "Email address cannot be found.";
	}
}
// this step validates the user's new password
elseif (isset($_POST['step2']) && $validRequest) {
	$errorMessage = validate_password($_POST['userPass'], $_POST['userPass2']);

	// if no errors
	if (!strlen($errorMessage)) {
		// hash the password
		$password = password_hash($_POST['userPass'], PASSWORD_DEFAULT);
		$datetime = $mysqli->real_escape_string(date("c"));

		// save the user's password
		$sql = "UPDATE Users SET
				Password    = '".$mysqli->real_escape_string($password)."',
				ResetCode   = NULL,
				UpdatedDate = '".$datetime."'
				WHERE User_ID = '".$mysqli->real_escape_string($_GET['i'])."'";
		$result = $mysqli->query($sql);

		if ($result) {
			$successMessage = "Your password has been updated. You should now be able to log in.";

			$validRequest = false;
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
