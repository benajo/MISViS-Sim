<?php
session_start();

date_default_timezone_set("Europe/London");

$mysqli = new mysqli("localhost", "ben", "password", "misvis");

if ($mysqli->connect_errno) {
	echo $mysqli->connect_error;
}
