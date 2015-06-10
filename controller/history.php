<?php
require_once 'core.inc.php';
if(loggedin()) {
	//require_once 'header.php';
	require_once 'dbconnect.inc.php';
	unset($_SESSION['id']);
	$idd = "";
	
	if((isset($_POST['sold']) || isset($_POST['delete'])) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST["check_list"])) {
			$string = "";
			foreach($_POST["check_list"] as $x) {
				$string = $string.$x." OR ID = ";
			}
			$string = substr($string, 0, strlen($string) - 9);
			if(isset($_POST['sold'])) {
				$query = "update posts set sold = 1 where ID = $string";
			} else if(isset($_POST['delete'])) {
				foreach($_POST["check_list"] as $x) {
					unlink('../posts/'.$x.'.jpg');
				}
				$query = "delete from posts where ID = $string";
			}
			if(!mysql_query($query)) {
				header('Location: '.$http_referer.'#failure');
			} else {
				header('Location: '.$http_referer.'#success');
			}
		}
	}
	
	if(isset($_POST['edit']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST["check_list"])) {
			foreach($_POST["check_list"] as $x) {
				$_SESSION["id"][$x] = $x;
				header('Location: ../editposts.php');
			}
		}
	}
	
} else {
	header('Location: ../index.php');
}
?>