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

function getuserdata($field,$table,$column,$key){
	$query = "select ".$field." from ".$table." where ".$column." = '".$key."'";
	if($query_run = mysql_query($query)){
		return mysql_fetch_assoc($query_run);
	}
}


function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>