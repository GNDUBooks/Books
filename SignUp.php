<?php
require_once 'core.inc.php';
require_once  'header.php';
$username = $name = $email = $pass = $cpass = $contact = "";
$usernameErr = $nameErr = $emailErr = $passErr = $cpassErr = $contactErr = "";

$flag = $flag1 = $flag2 = $flag3 = $flag4 = $flag5 = true;


if(loggedin()){
	header('Location: index.php');
} else {
	if(isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] == "POST"){
		require_once 'dbconnect.inc.php';
		$value = array('username' => "",'usernameErr' => "",'flag' => true);

		$value = checkusername($_POST['username']);
		$username = $value['username'];
		$usernameErr = $value['usernameErr'];
		$flag = $value['flag'];
		

		$value1 = array('name' => "", 'nameErr' => "", 'flag' => true);

		$value1 = checkname($_POST['name']);
		$name = $value1['name'];
		$nameErr = $value1['nameErr'];
		$flag1 = $value1['flag'];
		
		$value2 = array('pass' => "", 'passErr' => "", 'flag' => true);
		$value2 = checkpass($_POST['pass']);
		$pass = $value2['pass'];
		$passErr = $value2['passErr'];
		$flag2 = $value2['flag'];
	
		if (empty($_POST["cpass"])) {
			$cpassErr = "Confirmation password is required";
			$flag3 = false;
		} else {
			$cpass = test_input($_POST["cpass"]);
			// check if password match
			if ($pass != $cpass) {
				$cpassErr = "Passwords must match";
				$flag3 = false;
			}
		}
		
		$value3 = array('email' => "", 'emailErr' => "", 'flag' => true);
		$value3 = checkemail($_POST['email']);
		$email = $value3['email'];
		$emailErr = $value3['emailErr'];
		$flag4 = $value3['flag'];
		
		$value4 = array('contact' => "", 'contactErr' => "", 'flag' => true);
		$value4 = checkcontact($_POST['contact']);
		$contact = $value4['contact'];
		$contactErr = $value4['contactErr'];
		$flag5 = $value4['flag'];	
	
		if($flag && $flag1 && $flag2 && $flag3 && $flag4 && $flag5){

			while($flag) {
				$otp = rand(10000,50000);
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
<td><input type = "text" name = "username" value = "<?php echo $username;?>" /></td>
<td><span class="error">* <?php echo $usernameErr;?></span></td>
</tr>
<tr>
<td>Name</td>
<td><input type = "text" name = "name" value = "<?php echo $name;?>" /></td>
<td><span class="error">* <?php echo $nameErr;?></span></td>
</tr>
<tr>
<td>Password</td>
<td><input type = "password" name = "pass" value = "<?php echo $pass;?>"/></td>
<td><span class="error">* <?php echo $passErr;?></span></td>
</tr>
<tr>
<td>Confirm Password</td>
<td><input type = "password" name = "cpass" value = "<?php echo $cpass;?>"/></td>
<td><span class="error">* <?php echo $cpassErr;?></span></td>
</tr>
<tr>
<td>Email</td>
<td><input type = "email" name = "email" value = "<?php echo $email;?>"/></td>
<td><span class="error">* <?php echo $emailErr;?></span></td>
</tr>
<tr>
<td>Contact No</td>
<td><input type = "text" name = "contact" value = "<?php echo $contact;?>"/></td>
<td><span class="error"><?php echo $contactErr;?></span></td>
</tr>
<tr>
<td colspan = 2 align = "center"><input type = "submit" name = "submit" value = "Register" />/ <a href = "index.php">Already Member?</a></td>
</tr>
</table>
</form>
</body>
</html>