<?php
require_once 'header.php';
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
				if(mysql_query($query1)) {
					$to = "agndubooks@gmail.com";
					$subject = "report add $x";
					$message = "Add $x is reported by ".$_SESSION['user'];
					$headers = 'From: GNDUBooks <agndubooks@gmail.com>' . '\r\n' .
					   'Reply-To: agndubooks@gmail.com' . "\r\n" .
					   'X-Mailer: PHP/' . phpversion();
					if(mail($to, $subject, $message, $headers)){
						header('Location: '.$http_referer);
					} else {
						while(!mysql_query("delete from report where BookId = '".$x."'")){}
						die('Unable to send report');
					}
				} else {
					echo "This post has already been reported";
				}
			} else {
				echo "Your limit of reporting 5 posts has been reached";
			}
		} else {
			echo "Unable to process your request";
		}
	}
} else {
	header("Location: index.php");
}
?>