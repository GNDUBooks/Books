<?php
require 'core.inc.php';
require 'dbconnect.inc.php';
if(loggedin()){
	include 'profile.inc.php';
} else {
	include 'LogInForm.inc.php';
}

?>