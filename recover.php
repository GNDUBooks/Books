<?php
$email = $username = '';
$usernamemsg = $emailmsg = ''; 
$flag1 = $flag2 = true;
require_once 'core.inc.php';
require_once 'dbconnect.inc.php';
require_once 'header.php';
if(loggedin()){
	header('Location: index.php');
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
							$redirect_page = "index.php";
							header('Location: '.$redirect_page);
						} else {
							die('Failure: Password Recovery mail was not sent to your email address as there may be some problem!!!!');
						}
					} else {
						echo 'Unable to process your request';
					}
				} else {
					echo 'Invalid Email/Username combination';
				}
			} else {
				echo 'Unable to process your request';
			}
		} else {
			echo 'Username/Email doesn\'t exist in database';
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Recover Password</title>
</head>
<body>
<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<table>
<tr>
<td colspan = 2 align = "center"><h1>RECOVER PASSWORD</h1></td>
</tr>
<tr>
<td>Username: </td><td><input type = "text" name = "username" value = "<?php echo $username; ?>"></td>
<td><span class = "error">* <?php echo $usernamemsg; ?></span></td>
</tr>
<tr>
<td>Email: </td><td><input type = "email" name = "email" value = "<?php echo $email; ?>"></td>
<td><span class = "error">* <?php echo $emailmsg; ?></span></td>
</tr>
<tr>
<td colspan = 2 align = "center">
<input type = "submit" name = "submit" value = "Send new password to email" />
</td>
</tr>
</table>
</form>
</body>
</html>