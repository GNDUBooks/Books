<?php
//require_once 'header.php';
$email = $emailErr = $pass = $passErr = $newemail = $newemailErr = $conemail = $conemailErr = $error = "";
require_once 'core.inc.php';
$flag = $flag1 = $flag2 = $flag3 = $flag4 = true;
if(loggedin()){
	if(isset($_POST['changeemail']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if($_POST['email'] !== $_POST['newemail']) {
			require_once 'dbconnect.inc.php';
			$value1 = array('email' => "",'emailErr' => "", 'flag' => true);
			$value1 = checkemail($_POST['email']);
			$email = $value1['email'];
			$emailErr = $value1['emailErr'];
			$flag1 = !$value1['flag'];
			
			$value2 = array('pass' => "", 'passErr' => "", 'flag' => true);
			$value2 = checkpass($_POST['pass']);
			$pass = $value2['pass'];
			$passErr = $value2['passErr'];
			$flag2 = $value2['flag'];

			$value3 = array('email' => "",'emailErr' => "", 'flag' => true);
			$value3 = checkemail($_POST['newemail']);
			$newemail = $value3['email'];
			$newemailErr = $value3['emailErr'];
			$flag3 = $value3['flag'];
			
			if(!empty($_POST['conemail'])) {
				$conemail = test_input($_POST['conemail']);
				if($conemail != $newemail) {
					$conemailErr = "Email does not match";
					$flag4 = false;
				}
			} else {
				$conemailErr = "Confirm Email is required";
				$flag4 = false;
			}
			
			if($flag1 && $flag2 && $flag3 && $flag4) {
				$pass_hash = md5($pass);
				$query = "select login.Password,master.Email from login,master where login.Password = '".$pass_hash."' AND master.Email = '".$email."' AND login.Username = master.Username";
				if($query_run = mysql_query($query)) {
					if(mysql_num_rows($query_run) == 1) {
						$query_result = mysql_fetch_assoc($query_run);
						if($query_result['Password'] == $pass_hash) {
							if($query_result['Email'] == $email) {
								while($flag) {
									$otp = rand(50001,99999);
									$otp_hash = md5($otp);
									$query = "select otp from confirmation where otp = '".$otp_hash."'";
									if($query_run = mysql_query($query)) {
										if(mysql_num_rows($query_run) == 0) {
											$flag = false;
										}
									} else {
										$_SESSION['changemail']['error'] = 'Unable to process your request';
										header('Location: ' . $http_referer);
									}
								}
								$query = "insert into confirmation values ('".$otp_hash."','".$newemail."','".$_SESSION['user']."')";
								if(mysql_query($query)){
									$email = $newemail;
									require_once 'mail.php';
									//session_destroy();
									header('Location: ../confirm.php#changemailsent');
								} else {
									$_SESSION['changemail']['error'] = 'Unable to process your request';
									header('Location: ' . $http_referer);
								}
							} else {
								$_SESSION['changemail']['emailErr'] = "Invalid email";
								header('Location: ' . $http_referer);
							}
						} else {
							$_SESSION['changemail']['passErr'] = "Invalid Password";
							header('Location: ' . $http_referer);
						}
					} else {
						$_SESSION['changemail']['error'] = "Invalid email or password";
						header('Location: ' . $http_referer);
					}
				} else {
					$_SESSION['changemail']['error'] = 'Unable to process your request';
					header('Location: ' . $http_referer);
				}
			} else {
				$_SESSION['changemail']['error'] = $error;
				$_SESSION['changemail']['newmail'] = $newemail;
				$_SESSION['changemail']['newmailErr'] = $newemailErr;
				$_SESSION['changemail']['conmail'] = $conemail;
				$_SESSION['changemail']['conmailErr'] = $conemailErr;
				header('Location: ' . $http_referer);
			}
		} else {
			$_SESSION['changemail']['error'] = "New Email should differ from current";
			header('Location: ' . $http_referer);
		}
	}
} else {
	header('Location: ../index.php');
}
?>