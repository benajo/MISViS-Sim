--TEST--
validate_form() function - A basic test to see if it works. :)
--FILE--
<?php
include '../inc/functions.php'; // might need to adjust path if not in the same dir
var_dump(validate_form("test-email@@something.com", "email", "Email"));
var_dump(validate_form("test-email@something.com", "email", "Email"));
?>
--EXPECT--
string(32) "Invalid characters in Email.<br>"
NULL
