<?php
$email = $username = '';
$msg = $usernamemsg = $emailmsg = '';
$flag1 = $flag2 = true;
$redirect_page = "../index.php";
require_once 'core.inc.php';
require_once 'dbconnect.inc.php';
//require_once 'header.php';
if(loggedin()){
	header('Location: ../index.php');
} else {
	if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
		$value = checkusername($_POST['username']);
		$username = $value['username'];
		$usernamemsg = $value['usernameErr'];
		$flag1 = !$value['flag'];
		
		$value = checkemail($_POST['email']);
		$email = $value['email'];
		$emailmsg = $value['emailErr'];
		$flag2 = !$value['flag'];
		if($flag1 && $flag2 && $usernamemsg == 'Username already exists' && $emailmsg == 'Email Already Registered') {
			$query = "select Email,Username from master where Username = '".$username."' AND Email = '".$email."'";
			if($query_run = mysql_query($query)) {
				if(mysql_num_rows($query_run) == 1) {
					$email = mysql_result($query_run,0,'Email');
					$username = mysql_result($query_run,0,'Username');
					$pass = rand(100000,999999);
					$pass_hash = md5($pass);
					$query = "update login set password = '".$pass_hash."' where Username = '".$username."'";
					if(mysql_query($query)){
						$to      = $email;
						$subject = 'Password Recovery';
						$message = 'The new password for your GNDUBooks account is set to '.$pass;
						$headers = 'From: GNDUBooks <agndubooks@gndu.ac.in>' . "\r\n" .
								   'X-Mailer: PHP/' . phpversion();
						if(mail($to,$subject,$message,$headers)){
							header('Location: '.$redirect_page.'#passchanged');
						} else {
							header('Location: '.$redirect_page.'#mailnotsent');
						}
					} else {
						header('Location: '.$redirect_page.'#recoverErr');
					}
				} else {
					$_SESSION['recover']['username'] = $username;
					$_SESSION['recover']['email'] = $email;
					$_SESSION['recover']['msg'] = 'Invalid Email/Username combination';
					header('Location: ../recover.php');
				}
			} else {
				header('Location: '.$redirect_page.'#recoverErr');
			}
		} else {
			$_SESSION['recover']['username'] = $username;
			$_SESSION['recover']['email'] = $email;
			$_SESSION['recover']['msg'] = 'Invalid Email/Username combination';
			header('Location: ../recover.php');
		}
	}
}
?>