<?php
session_start();
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
	$http_referer = $_SERVER['HTTP_REFERER'];
	foreach($_SESSION["search"] as $x) {
		$y = $x['value'];
		if(isset($_POST['reportadd']["$y"]) && $x['flag']){
			$to = "agndubooks@gmail.com";
			$subject = "report add $y";
			$message = "Add $y is reported by ".$_SESSION['user'];
			$headers = 'From: GNDUBooks <agndubooks@gmail.com>' . '\r\n' .
			   'Reply-To: agndubooks@gmail.com' . "\r\n" .
			   'X-Mailer: PHP/' . phpversion();
			if(mail($to, $subject, $message, $headers)){
				$_SESSION["search"][$x]['flag'] = false;
				header('Location: '.$http_referer);
			} else {
				die('Unable to send report');
			}
		}
	}
} else {
	header("Location: index.php");
}
?>