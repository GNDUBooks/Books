<?php
//require_once '../header.php';
$email = $otp = $emailErr = $otpErr = $error = '';
$flag1 = $flag2 = true;
require_once 'core.inc.php';
//if(loggedin()){
//	header('Location: index.php');
//} else {
	if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST"){
		if (empty($_POST['email'])) {
			$emailErr = "Email is required";
			$flag1 = false;
		} else {
			$email = test_input($_POST["email"]);
			// check if e-mail address is well-formed
			if(strlen($email)>50){
				$emailErr = "Email length exceeds 50 character limit";
				$flag1 = false;
			}
		}
		
		if (empty($_POST["otp"])) {
			$otpErr = "One Time Password is required";
			$flag3 = false;
		} else {
			$otp = test_input($_POST["otp"]);
			// check if password only contains letters and numbers
			if (!preg_match("/^[0-9]{5}$/",$otp)) {
				$otpErr = "Only numbers are allowed and upto length 5";
				$flag2 = false;
			}
		}
		
		require_once 'dbconnect.inc.php';
		if($flag1 && $flag2) {
			$otp_hash = md5($otp);
			if($otp >= 10000 && $otp <= 50000){
				$query = "select c.*, t.* from confirmation as c,temp as t where c.Email = '".$email."' AND c.OTP = '".$otp_hash."' AND c.OTP = t.OTP";
				if($query_run = mysql_query($query)) {
					if(mysql_num_rows($query_run) == 1) {
						$q_r = mysql_fetch_assoc($query_run);
						$contact = (empty($q_r['ContactNo']) ? 'NULL' : $q_r['ContactNo']);
						$query1 = "insert into master (Username,Name,Email,ContactNo) values('".$q_r['Username']."','".$q_r['Name']."','".$email."', ".$contact.")";
						$query2 = "insert into login values ('".$q_r['Username']."','".$q_r['Password']."')";
						$query3 = "delete from confirmation where OTP = '".$otp_hash."'";
						if(mysql_query($query1) && mysql_query($query2) && mysql_query($query3)){
							header('Location: ../index.php#registrationcomplete');
						} else {
							$_SESSION['confirm']['error'] = 'Error in processing your request... Try again after some time';
							$_SESSION['confirm']['email'] = $email;
							header('Location: ../confirm.php');
						}
					} else {
						$_SESSION['confirm']['otpErr'] = 'Please enter correct OTP.';
						$_SESSION['confirm']['email'] = $email;
						header('Location: ../confirm.php');
					}
				} else {
					$_SESSION['confirm']['error'] = 'Please enter correct information.';
					$_SESSION['confirm']['email'] = $email;
					header('Location: ../confirm.php');
				}
			} else if($otp > 50000 && $otp <= 99999) {
				$otp_hash = md5($otp);
				$query = "select Username from confirmation where OTP = '".$otp_hash."' AND Email = '".$email."'";
				if($query_run = mysql_query($query)) {
					if(mysql_num_rows($query_run) == 1) {
						$query_result = mysql_fetch_assoc($query_run);
						$username = $query_result['Username'];
						$query = "update master set Email = '".$email."' where Username = '".$username."'";
						$query1 = "delete from confirmation where OTP = '".$otp_hash."'";
						if(mysql_query($query) && mysql_query($query1)) {
							header('Location: ../index.php#emailchanged');
						} else {
							$_SESSION['confirm']['error'] = 'Unable to process your request';
							$_SESSION['confirm']['email'] = $email;
							header('Location: ../confirm.php');
						}
					} else {
						$_SESSION['confirm']['otpErr'] = 'invalid OTP for given Email';
						$_SESSION['confirm']['email'] = $email;
						header('Location: ../confirm.php');
					}
				} else {
					$_SESSION['confirm']['error'] = 'Unable to process your request';
					$_SESSION['confirm']['email'] = $email;
					header('Location: ../confirm.php');
				}
			}
		}
	}	
//}
?>