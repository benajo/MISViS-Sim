--TEST--
validate_password() function - A basic regression test.
--FILE--
<?php
include '../inc/functions.php';

var_dump(validate_password("password",    ""));
var_dump(validate_password("",            "password"));
var_dump(validate_password("hello",       "hello"));
var_dump(validate_password("helloworld",  "helloworld!"));
var_dump(validate_password("these-match", "these-match"));
?>
--EXPECT--
string(41) "Please complete both password fields.<br>"
string(41) "Please complete both password fields.<br>"
string(43) "Password length must be greater than 8.<br>"
string(27) "Passwords do not match.<br>"
NULL