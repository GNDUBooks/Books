<?php
require_once 'core.inc.php';
$to      = $email;
$subject = 'Verification Mail';
$message = 'One time password for verification of your account is '.$otp.'. To verify your account, submit this OTP here: http://'.$_SERVER['SERVER_ADDR'].'/confirm.php';
$headers = 'From: GNDUBooks <localserver@tss-gndu.com>' . "\r\n" .
           'Reply-To: localserver@tss-gndu.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if(mail($to, $subject, $message, $headers)) {
	$redirect_page = "../confirm.php";
	header('Location: '.$redirect_page.'#temporaryaccountcreated');
} else {
	$query = "delete from confirmation where OTP = '".$otp_hash."'";
	if(mysql_query($query)){	
		$flag = false;
		header('Location: ../index.php#temporaryaccountnotcreated');
	}
}
?>
