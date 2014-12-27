<?php
$email = $pass = $otp = '';
$emailErr = $passErr = $otpErr = $error ="";
$flag = true;
require_once 'core.inc.php';
if(loggedin()){
	header('Location: index.php');
} else {
	if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == "POST"){
		require_once 'core.inc.php';
		if (empty($_POST['email'])) {
			$emailErr = "Email is required";
			$flag = false;
		} else {
			$email = test_input($_POST["email"]);
			// check if e-mail address is well-formed
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format";
				$flag = false;
			}
			if(strlen($email)>50){
				$emailErr = "Email length exceeds 50 character limit";
				$flag = false;
			}
		}
		
		if (empty($_POST['pass'])) {
			$passErr = "Password is required";
			$flag = false;
		} else {
			$pass = test_input($_POST['pass']);
			// check if password only contains letters and numbers
			if (!preg_match("/^[a-zA-Z0-9]{6,20}$/",$pass)) {
				$passErr = "Only letters and numbers are allowed and length between 6 and 20";
				$flag = false;
			}
		}
		
		if (empty($_POST["otp"])) {
			$otpErr = "One Time Password is required";
			$flag = false;
		} else {
			$otp = test_input($_POST["otp"]);
			// check if password only contains letters and numbers
			if (!preg_match("/^[0-9]{1,5}$/",$otp)) {
				$otpErr = "Only numbers are allowed and upto length 5";
				$flag = false;
			}
		}
		
		if($flag){
			require_once 'dbconnect.inc.php';
			$otp_hash = md5($otp);
			$pass_hash = md5($pass);
			$query = "select * from confirmation where Email = '".$email."' AND OTP = '".$otp_hash."' AND Password='".$pass_hash."'";
			if($query_run = mysql_query($query)){
				if(mysql_num_rows($query_run)==1){
					$query_result = getuserdata('*','temp','OTP',$otp_hash);
					$query1 = "insert into master (Username,Name,Email,ContactNo) values('".$query_result['Username']."','".$query_result['Name']."','".$query_result['Email']."','".$query_result['ContactNo']."')";
					$query2 = "insert into login values ('".$query_result['Username']."','".$pass_hash."')";
					$query3 = "delete from confirmation where OTP = '".$otp_hash."'";
					if(mysql_query($query1) && mysql_query($query2) && mysql_query($query3)){
						header('Location: index.php');
					} else {
						$error = 'Error in processing your request... Try after some time';
					}
				} else {
					echo 'Please enter correct information.';
				}
			} else{
				$error = 'Error in processing your request... Try after some time';
			}
		}
	}	
}

echo $error;
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
<td align = "right"><input type = "email" name="email" size = 40 value = "<?php echo $email?>" /><span class = "error">* <?php echo $emailErr; ?></td>
</tr>
<tr><td>Password: </td>
<td align = "right"><input type = "password" name = "pass" size = 40 /><span class = "error">* <?php echo $passErr; ?></td>
</tr>
<tr><td>One Time Password: </td>
<td align = "right"><input type = "text" name = "otp" size = 40/><span class = "error">* <?php echo $otpErr; ?></td>
</tr>
<tr><td colspan=2 align="center"><input type="submit" name="submit" value="CONFIRM" /></td></tr>
</table>
</form>
</body>
</html>