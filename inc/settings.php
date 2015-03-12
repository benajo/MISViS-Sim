<?php
session_start();

// default timezone on the local mac machine wasn't UK time
date_default_timezone_set("Europe/London");

// DB connection
$mysqli = new mysqli("localhost", "ben", "password", "misvis");

// display a connection error if it fails
if ($mysqli->connect_errno) {
	echo $mysqli->connect_error;
}
