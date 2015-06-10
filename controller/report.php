<?php
require_once '../header.php';
session_start();
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
	$http_referer = $_SERVER['HTTP_REFERER'];
	require_once 'dbconnect.inc.php';
	foreach($_POST["reportadd"] as $x => $x_value) {
		$date = date("Y-m-d");
		$query = "select count(BookId) as no from report where Username = '".$_SESSION['user']."' and DateOfReport like '".$date."_________'";
		if($query_run = mysql_query($query)){
			if(mysql_result($query_run,0,'no') < 5){
				$query1 = "insert into report (BookId,Username) values ('".$x."','".$_SESSION['user']."')";
				$query2 = "update posts set NoReport = NoReport + 1 where ID = '".$x."'";
				if(mysql_query($query1) && mysql_query($query2)) {
					$to = "agndubooks@gmail.com";
					$subject = "report add $x";
					$message = "Add $x is reported by ".$_SESSION['user'];
					$headers = 'From: GNDUBooks <agndubooks@gmail.com>' . '\r\n' .
					   'Reply-To: agndubooks@gmail.com' . "\r\n" .
					   'X-Mailer: PHP/' . phpversion();
					if(mail($to, $subject, $message, $headers)){
						header('Location: '.$http_referer.'#reported');
					} else {
						while(!mysql_query("delete from report where BookId = '".$x."'") && !mysql_query("update posts set NoRepor = NoReport - 1 where ID = '".$x."'")){}
						header('Location: '.$http_referer.'#notreported');
					}
				} else {
					header('Location: '.$http_referer.'#alreadyreported');
				}
			} else {
				header('Location: '.$http_referer.'#reportlimitreached');
			}
		} else {
			header('Location: '.$http_referer.'#dbproblem');
		}
	}
} else {
	header("Location: ../index.php");
}
?>