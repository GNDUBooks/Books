<?php
require_once 'core.inc.php';
$to      = $email;
$subject = 'Verification Mail from GNDUBooks';
$message = 'One time password for verification of your account is '.$otp;
$headers = 'From: GNDUBooks <agndubooks@gmail.com>' . "\r\n" .
           'Reply-To: agndubooks@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if(mail($to, $subject, $message, $headers)) {
	$redirect_page = "confirm.php";
	header('Location: '.$redirect_page);
} else {
	$query = "delete from confirmation where OTP = '".$otp_hash."'";
	$flag = true;
	while($flag){
		if(mysql_query($query)){	
			$flag = false;
			die('Failure: Varification Mail was not sent to your email address as there may be some problem!!!!');
		}
	}
}
?>