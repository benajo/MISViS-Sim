--TEST--
random_code() function - A basic regression test.
--FILE--
<?php
include '../inc/functions.php';

var_dump(strlen(random_code(2)));
var_dump(strlen(random_code(8)));
var_dump(strlen(random_code(19)));
var_dump(strlen(random_code(33)));
var_dump(strlen(random_code(120)));
var_dump(strlen(random_code(512)));
?>
--EXPECT--
int(2)
int(8)
int(19)
int(33)
int(120)
int(512)