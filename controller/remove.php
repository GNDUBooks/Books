<?php
require_once 'core.inc.php';
if(loggedin()){
	require_once 'dbconnect.inc.php';
	$query = "update master set Link_Photo = 0 where Username = '".$_SESSION['user']."'";
	if(mysql_query($query)){
		unlink('../pro_photos/'.$_SESSION['user'].'.jpg');
		header('Location: ../index.php');
	} else {
		header('Location: ../index.php');
	}
} else {
	header('Location: ../index.php');
}
?>