--TEST--
validate_form() function - A basic regression test.
--FILE--
<?php
include '../inc/functions.php';

var_dump(validate_form("test-email@@something.com", "email", "Email"));
var_dump(validate_form("test-email@something.com",  "email", "Email"));

var_dump(validate_form("Ben Jovanic",         "name", "Name"));
var_dump(validate_form("Anne-Marie O'Connor", "name", "Name"));
var_dump(validate_form("John.Smith",          "name", "Name"));

var_dump(validate_form("helloworld",  "alpha",   "Test 1"));
var_dump(validate_form("hello1",      "alnum",   "Test 2"));
var_dump(validate_form("123456789",   "num",     "Test 3"));
var_dump(validate_form("hello world", "alpha_s", "Test 4"));
var_dump(validate_form("hello 1",     "alnum_s", "Test 5"));
var_dump(validate_form("1 2 3 4",     "num_s",   "Test 6"));
?>
--EXPECT--
string(32) "Invalid characters in Email.<br>"
NULL
NULL
NULL
string(31) "Invalid characters in Name.<br>"
NULL
NULL
NULL
NULL
NULL
NULL