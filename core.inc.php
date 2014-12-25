<?php
ob_start();
session_start();
$current_file = $_SERVER['SCRIPT_NAME'];

if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
	$http_referer = $_SERVER['HTTP_REFERER'];
}

function loggedin() {
	if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
		return true;
	} else {
		return false;
	}
}

function getuserfield($field){
	$query = "select ".$field." from master where username = '".$_SESSION['user']."'";
	if($query_run = mysql_query($query)){
		return mysql_result($query_run, 0, $field);
	}
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>