<?php
//require_once 'header.php';
require_once '../core.inc.php';
require_once '../dbconnect.inc.php';
$error = '';
if(isset($_POST['username']) && isset($_POST['pass'])){
	$username = strtolower(test_input($_POST['username']));
	$pass = test_input($_POST['pass']);
	
	if(!empty($username) && !empty($pass)){
		$pass_hash = md5($pass);
		$query = "select Username from login where Username = '".mysql_real_escape_string($username)."' AND Password = '".mysql_real_escape_string($pass_hash)."'";
		if($query_run = mysql_query($query)){
			$query_num_rows = mysql_num_rows($query_run);
			if($query_num_rows == 0) {
				$error = 'Invalid username/password combination.';
				$_SESSION['LogIn']['error'] = $error;
				header('Location: '.$http_referer);
			} else if($query_num_rows == 1) {
				$user = mysql_result($query_run,0,'Username');
				$_SESSION['user'] = $user;
				header('Location: index.php');
			}
		}	
	} else {
		$error = 'You must supply a username and password.';
		$_SESSION['LogIn']['error'] = $error;
		header('Location: '.$http_referer);
	}
}
?>