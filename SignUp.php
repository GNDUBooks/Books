<?php
require_once 'core.inc.php';
$username = $name = $email = $pass = $cpass = $contact = "";
$usernameErr = $nameErr = $emailErr = $passErr = $cpassErr = $contactErr = "";
$flag = true;
if(loggedin()){
	header('Location: index.php');
} else {
	if(isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] == "POST"){
		require_once 'dbconnect.inc.php';
		$value = checkusername($_POST['username']);
		$username = $value['username'];
		$usernameErr = $value['usernameErr'];
		$flag = $value['flag'];
		
		$value = checkname($_POST['name']);
		$name = $value['name'];
		$nameErr = $value['nameErr'];
		$flag = $value['flag'];
		
		$value = checkpass($_POST['pass']);
		$pass = $value['pass'];
		$passErr = $value['passErr'];
		$flag = $value['flag'];
		
		if (empty($_POST["cpass"])) {
			$cpassErr = "Confirmation password is required";
			$flag = false;
		} else {
			$cpass = test_input($_POST["cpass"]);
			// check if password match
			if ($pass != $cpass) {
				$cpassErr = "Passwords must match";
				$flag = false;
			}
		}
		
		$value = checkemail($_POST['email']);
		$email = $value['email'];
		$emailErr = $value['emailErr'];
		$flag = $value['flag'];
		
		$value = checkcontact($_POST['contact']);
		$contact = $value['contact'];
		$contactErr = $value['contactErr'];
		$flag = $value['flag'];	
	
		if($flag){
			while($flag) {
				$otp = rand(1,99999);
				$otp_hash = md5($otp);
				$query = "select otp from confirmation where otp = '".$otp_hash."'";
				if($query_run = mysql_query($query)) {
					if(mysql_num_rows($query_run) == 0) {
						$flag = false;
					}
				}
			}
			$pass_hash = md5($pass);
			$query1 = "insert into confirmation values ('$otp_hash','$email','$pass_hash')";
			$query2 = "insert into temp values ('$username','$name','$email','$contact','$otp_hash')";
			if($query_run1 = mysql_query($query1) && $query_run2 = mysql_query($query2)){
				require_once 'mail.php';
			} else {
				echo 'retry after sometime as some error occured';
			}
		}
	}
}
?>
<!DOCTYPE html>
<head>
<title>
Sign Up
</title>
</head>
<body>
<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
<tr>
<td colspan = 2><h1 align = "center">SignUp</h1></td>
</tr>
<tr>
<td>Username</td>
<td><input type = "text" name = "username" value = "<?php echo $username;?>" /><span class="error">* <?php echo $usernameErr;?></span></td>
</tr>
<tr>
<td>Name</td>
<td><input type = "text" name = "name" value = "<?php echo $name;?>" /><span class="error">* <?php echo $nameErr;?></span></td>
</tr>
<tr>
<td>Password</td>
<td><input type = "password" name = "pass" value = "<?php echo $pass;?>"/><span class="error">* <?php echo $passErr;?></span></td>
</tr>
<tr>
<td>Confirm Password</td>
<td><input type = "password" name = "cpass" value = "<?php echo $cpass;?>"/><span class="error">* <?php echo $cpassErr;?></span></td>
</tr>
<tr>
<td>Email</td>
<td><input type = "email" name = "email" value = "<?php echo $email;?>"/><span class="error">* <?php echo $emailErr;?></span></td>
</tr>
<tr>
<td>Contact No</td>
<td><input type = "text" name = "contact" value = "<?php echo $contact;?>"/><span class="error"><?php echo $contactErr;?></span></td>
</tr>
<tr>
<td colspan = 2 align = "center"><input type = "submit" name = "submit" value = "Register" />/ <a href = "index.php">Already Member?</a></td>
</tr>
</table>
</form>
</body>
</html>