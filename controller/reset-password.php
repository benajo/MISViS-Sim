<?php
$validRequest = false;

if (isset($_GET['c']) || isset($_GET['i'])) {
	$code = $mysqli->real_escape_string($_GET['c']);
	$userId = $mysqli->real_escape_string($_GET['i']);

	$sql = "SELECT User_ID FROM Users
			WHERE ResetCode = '$code'
			AND User_ID = '$userId'";
	$result = $mysqli->query($sql);

	if ($result->num_rows) {
		$validRequest = true;
	}
	else {
		$errorMessage = "Reset code is invalid.";
	}
}

if (isset($_POST['step1'])) {
	$email = $mysqli->real_escape_string($_POST['userEmail']);

	$sql = "SELECT User_ID, FirstName, LastName FROM Users
			WHERE Email = '$email'";
	$result = $mysqli->query($sql);

	if ($result->num_rows) {
		$row = $result->fetch_assoc();

		$code = random_code(32);

		$message  = "Dear ".$row['FirstName']." ".$row['LastName'].",\n\n";
		$message .= "Please copy and paste the following link into your browser to reset your password:\n\n";
		$message .= SITE_URL."reset-password.php?c=$code&i=".$row['User_ID'];


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
		// $mail->isHTML(true);

		$mail->Subject = "MISViS Sim :: Password reset";
		$mail->Body    = $message;


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
elseif (isset($_POST['step2']) && $validRequest) {
	$errorMessage = validate_password($_POST['userPass'], $_POST['userPass2']);

	if (!strlen($errorMessage)) {
		$password = password_hash($_POST['userPass'], PASSWORD_DEFAULT);
		$datetime = $mysqli->real_escape_string(date("c"));

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
