<?php
session_start();

// default timezone on the local mac machine wasn't UK time
date_default_timezone_set("Europe/London");

// DB connection
$host = "localhost";
$user = "ben";
$password = "password";
$database = "misvis";

$mysqli = new mysqli($host, $user, $password, $database);

// display a connection error if it fails
if ($mysqli->connect_errno) {
	echo $mysqli->connect_error;
}
