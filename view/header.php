<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo ucwords(str_replace("-", " ", PAGE)); ?></title>

<link rel="stylesheet" type="text/css" href="style/normalize.css">
<link rel="stylesheet" type="text/css" href="style/style.css">

<script type="text/javascript" src="js/vextab-div.js"></script>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src='js/simulation-functions.js'></script>
<script type="text/javascript" src='js/piece-edit-functions.js'></script>
</head>

<body>
<div id="page-container">
	<div id="page-header">
		<div id="page-logo">MISViS Sim</div>
		<div id="page-nav">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="about.php">About</a></li>

				<?php if (isset($_SESSION['auth']) && $_SESSION['auth']) { ?>
					<li><a href="account.php">Account</a></li>
					<li><a href="account.php?logout">Logout</a></li>
				<?php } else { ?>
					<li><a href="register.php">Register</a></li>
					<li><a href="login.php">Login</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div id="page-content">
