<?php
/**
 * @author Ben Jovanic
 * @version 2015-02-13
 *
 * Generates a random string of letters and numbers to the specified length.
 */
function random_code($n)
{
	$code = "";

	$chars = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M",
		"N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
		"a","b","c","d","e","f","g","h","i","j","k","l","m",
		"n","o","p","q","r","s","t","u","v","w","x","y","z",
		"0","1","2","3","4","5","6","7","8","9"
	);

	$charsLength = count($chars) - 1;

	for ($i=0; $i < $n; $i++) {
		$code .= $chars[rand(1, $charsLength)];
	}

	return $code;
}

/**
 * @author Ben Jovanic
 * @version 2015-02-13
 *
 * Validates HTML form fields and returns an error if there is one.
 */
function validate_form($data, $type, $name)
{
	$invalid = "Invalid characters in {$name}.<br>";

	if (empty($data)) {
		if ($type == "req") {
			return "{$name} required.<br>";
		}
	}
	else {
		switch ($type) {
			case "email": // validates input is an email address
				if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
					return $invalid;
				}
				break;

			case "name": // validates the input is a person's name
				if (!preg_match("/^([a-z\'\-\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alpha": // alphabetic characters only
				if (!preg_match("/^([a-z]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alnum": // alphabetic and number characters only
				if (!preg_match("/^([a-z0-9]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "num": // number characters only
				if (!preg_match("/^([0-9]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alpha_s": // same as "alpha", plus spaces
				if (!preg_match("/^([a-z\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alnum_s": // same as "alnum", plus spaces
				if (!preg_match("/^([a-z0-9\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "num_s": // same as "num", plus spaces
				if (!preg_match("/^([0-9\s]+)$/i", $data)) {
					return $invalid;
				}
				break;
		}
	}
}

/**
 * @author Ben Jovanic
 * @version 2015-02-13
 *
 * Ensures a user's password is not empty, both passwords match, and
 * the length is 8 or more characters long.
 */
function validate_password($p1, $p2)
{
	if (empty($p1) || empty($p2)) {
		return "Please complete both password fields.<br>";
	}

	if (strlen($p1) < 8) {
		return "Password length must be greater than 8.<br>";
	}

	if ($p1 != $p2) {
		return "Passwords do not match.<br>";
	}
}

/**
 * @author Ben Jovanic
 * @version 2015-02-13
 *
 * Outputs error and success messages.
 */
function display_messages()
{
	global $errorMessage, $successMessage;

	echo "<div class='displayMessages'>\n";

	if (strlen($errorMessage)) {
		echo "<p class='error-message'>\n";
		echo $errorMessage . "\n";
		echo "</p>\n";
	}

	if (strlen($successMessage)) {
		echo "<p class='success-message'>\n";
		echo $successMessage."\n";
		echo "</p>\n";
	}

	echo "</div>\n";
}
