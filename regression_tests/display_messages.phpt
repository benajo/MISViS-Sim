--TEST--
display_messages() function - A basic regression test.
--FILE--
<?php
include '../inc/functions.php';

$errorMessage = "";
$successMessage = "";
// will output JUST the displayMessages div
display_messages();

$errorMessage = "There has been an unexpected error.";
$successMessage = "";
// just error message
display_messages();

$errorMessage = "";
$successMessage = "Your details have been updated successfully.";
// just success message
display_messages();

$errorMessage = "There has been an unexpected error.";
$successMessage = "Your details have been updated successfully.";
// will include both success and error messages
display_messages();
?>
--EXPECT--
<div class='displayMessages'>
</div>
<div class='displayMessages'>
<p class='error-message'>
There has been an unexpected error.
</p>
</div>
<div class='displayMessages'>
<p class='success-message'>
Your details have been updated successfully.
</p>
</div>
<div class='displayMessages'>
<p class='error-message'>
There has been an unexpected error.
</p>
<p class='success-message'>
Your details have been updated successfully.
</p>
</div>