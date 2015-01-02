<?php
require_once 'header.php';
$email = $pass = $otp = $username = $emailErr = $passErr = $otpErr = $usernameErr = $error = '';
$flag1 = $flag2 = $flag3 = $flag4 = true;
require_once 'core.inc.php';
if(loggedin()){
	header('Location: index.php');
} else {
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
		
		if (empty($_POST['pass'])) {
			$passErr = "Password is required";
			$flag2 = false;
		} else {
			$pass = test_input($_POST['pass']);
			// check if password only contains letters and numbers
			if (!preg_match("/^[a-zA-Z0-9]{6,20}$/",$pass)) {
				$passErr = "Only letters and numbers are allowed and length between 6 and 20";
				$flag2 = false;
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
				$flag3 = false;
			}
		}
		
		require_once 'dbconnect.inc.php';
		$value = array('username' => "", 'usernameErr' => "", 'flag' => true);
		echo $_POST['username'];
		$value = checkusername($_POST['username']);
		$username = $value['username'];
		$usernameErr = $value['usernameErr'];
		$flag4 = !$value['flag'];
				
		if($flag1 && $flag2 && $flag3 && $flag4 && $usernameErr == 'Username already exists') {
			require_once 'dbconnect.inc.php';
			$otp_hash = md5($otp);
			$pass_hash = md5($pass);
			if($otp >= 10000 && $otp <= 50000){
				$query = "select c.*, t.* from confirmation as c,temp as t where c.Email = '".$email."' AND c.OTP = '".$otp_hash."' AND c.Password='".$pass_hash."' AND t.username = '".$username."' AND c.OTP = t.OTP";
				if($query_run = mysql_query($query)) {
					if(mysql_num_rows($query_run) == 1) {
						$q_r = mysql_fetch_assoc($query_run);
						$query1 = "insert into master (Username,Name,Email,ContactNo) values('".$username."','".$q_r['Name']."','".$email."','".$q_r['ContactNo']."')";
						$query2 = "insert into login values ('".$q_r['Username']."','".$q_r['Password']."')";
						$query3 = "delete from confirmation where OTP = '".$otp_hash."'";
						if(mysql_query($query1) && mysql_query($query2) && mysql_query($query3)){
							header('Location: index.php');
						} else {
							$error = 'Error in processing your request... Try after some time';
						}
					}
				} else {
					echo 'Please enter correct information.';
				}
			} else if($otp > 50000 && $otp <= 99999) {
				$pass_hash = md5($pass);
				$otp_hash = md5($otp);
				$query = "select Username from login where Username = '".$username."' AND Password = '".$pass_hash."'";
				$query1 = "select Email,Password from confirmation where OTP = '".$otp_hash."' AND Password = '".$pass_hash."'";
				if($query_run = mysql_query($query)) {
					if($query_run1 = mysql_query($query1)) {
						if(mysql_num_rows($query_run) == 1) {
							if(mysql_num_rows($query_run1) == 1) {
								$query = "update master set Email = '".$email."' where Username = '".$username."'";
								$query1 = "delete from confirmation where OTP = '".$otp_hash."'";
								if(mysql_query($query) && mysql_query($query1)) {
									header('Location: index.php');
								} else {
									echo 'Unable to process your request';
								}
							} else {
								$otpErr = 'invalid OTP for given Email';
							}
						} else {
							$passErr = 'invalid username/password combination';
						}
					} else {
						echo 'Unable to process your request';
					}
				} else {
					echo 'Unable to process your request';
				}
			}
		}
	}	
}


?>

<!DOCTYPE html>
<html>
<head>
<title>CONFIRMATION PAGE</title>
</head>
<body> 
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
<table cellspacing=20 >
<tr><td colspan=2 align="center"><h1>CONFIRMATION PAGE</h1></td></tr>
<tr><td>EMail: </td>
<td align = "right"><input type = "email" name="email" size = 40 value = "<?php echo $email?>" /></td>
<td><span class = "error">* <?php echo $emailErr; ?></td>
</tr>
<tr>
<td>Username: </td>
<td><input type = "text" name = "username" value = "<?php echo $username; ?>" size = 40></td>
<td><span class = "error">* 
<?php
if($usernameErr != 'Username already exists') {
	echo $usernameErr;
}
?></span></td>
</tr>
<tr><td>Password: </td>
<td align = "right"><input type = "password" name = "pass" size = 40 /></td>
<td><span class = "error">* <?php echo $passErr; ?></td>
</tr>
<tr><td>One Time Password: </td>
<td align = "right"><input type = "text" name = "otp" size = 40/></td>
<td><span class = "error">* <?php echo $otpErr; ?></td>
</tr>
<tr><td colspan=2 align="center"><input type="submit" name="submit" value="CONFIRM" /></td></tr>
</table>
</form>
</body>
</html>