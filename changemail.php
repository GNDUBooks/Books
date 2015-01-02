<?php
require_once 'header.php';
$email = $emailErr = $pass = $passErr = $newemail = $newemailErr = $conemail = $conemailErr = "";
require_once 'core.inc.php';
$flag = $flag1 = $flag2 = $flag3 = $flag4 = true;
if(loggedin()){
	if(isset($_POST['changeemail']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
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
			if($emailErr == 'Email Already Registered') {
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
									}
								}
								$query = "insert into confirmation values ('".$otp_hash."','".$newemail."','".$query_result['Password']."')";
								if(mysql_query($query)){
									$email = $newemail;
									require_once 'mail.php';
									session_destroy();
									header('Location: confirm.php');
								} else {
									echo 'Unable to process your request';
								}
							} else {
								$emailErr = "Inavalid email";
							}
						} else {
							$passErr = "Invalid Password";
						}
					}
				}
			}
		}
	}
} else {
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Email</title>
</head>
<body>
<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
<table>
<tr>
<td colspan = 2 align = "center"><h1>CHANGE EMAIL</h1></td>
</tr>
<tr>
<td>Current Email: </td>
<td><input type = "email" name = "email" value = "<?php echo $email; ?>"></td>
<td><span class = "error">* 
<?php
if($emailErr != 'Email Already Registered'){
	echo $emailErr;
}
?>
<td>
</tr>
<tr>
<td>Current Password: </td>
<td><input type = "password" name = "pass"></td>
<td><span class = "error">* <?php echo $passErr; ?></span></td>
</tr>
<tr>
<td>New Email: </td>
<td><input type = "email" name = "newemail" value = "<?php echo $newemail; ?>"></td>
<td><span class = "error">* <?php echo $newemailErr; ?></span></td>
</tr>
<tr>
<td>Confirm New Email: </td>
<td><input type = "email" name = "conemail" value = "<?php echo $conemail; ?>"></td>
<td><span class = "error">* <?php echo $conemailErr; ?></span>
</tr>
<tr>
<td colspan = 2 align = "center"><input type = "submit" name = "changeemail" value = "Change Email"></td>
</tr>
</table>
</form>
</body>
</html>