<?php
require_once 'header.php';
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
							header('Location: index.php#registrationcomplete');
						} else {
							$error = 'Error in processing your request... Try again after some time';
						}
					} else {
						$error = 'Please enter correct OTP.';
					}
				} else {
					$error = 'Please enter correct information.';
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
							header('Location: index.php#emailchanged');
						} else {
							$error = 'Unable to process your request';
						}
					} else {
						$otpErr = 'invalid OTP for given Email';
					}
				} else {
					$error = 'Unable to process your request';
				}
			}
		}
	}	
//}
?>

<!DOCTYPE html>
<html>
<head>
<title>CONFIRMATION PAGE</title>
<script type="text/javascript" src="js/message.js"></script>
<script type="text/javascript" src="js/confirm-hash.js"></script>
<style>
.message {
	padding: 20px;
	color:white;
	background-color:black;
	margin: 10px auto;
	width: 50%;
	border-radius: 5px;
	box-shadow: 0px 0px 10px #783535;
	display:none;
	text-align:center;
}
.message.error {
	background-color: rgba(255,0,0,0.7);
}
.message.success {
	background-color: rgba(0,255,0,0.7);
}
</style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
<div id="alert-message" class="message"></div>
<table align="center" cellpadding="10" style="background-color:rgba(255,255,255,0.75); width:40%">
<tr><td colspan=2 align="center"><h2>CONFIRMATION PAGE</h1></td></tr>
<tr><td>EMail: </td>
<td align = "right" style="color:red"><input type = "email" name="email" size = 40 value = "<?php echo $email?>" /> *<br>
<?php echo $emailErr; ?></td></tr>
<tr><td>OTP: </td>
<td align = "right" style="color:red"><input type = "text" name = "otp" size = 40/> *<br>
<?php echo $otpErr; ?></td>
</tr>
<tr><td colspan=2 style="color:red">
<?php
echo $error;
?></td></tr>
<tr><td colspan=2 align="center"><input type="submit" name="submit" value="CONFIRM" /></td></tr>
</table>
</form>
</body>
</html>