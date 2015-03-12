<?php
// check the auth session variable has been set
if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
	header("Location: login.php?auth=error");
	exit;
}
