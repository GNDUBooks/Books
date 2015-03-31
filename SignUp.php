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
		$username = strtolower($value['username']);
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
			$contact1 = empty($contact) ? 'NULL' : $contact;
			$query1 = "insert into confirmation values ('$otp_hash','$email','$username')";
			$query2 = "insert into temp values ('$pass_hash','$name','$email',$contact1,'$otp_hash')";
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
<table align="center" cellpadding="10" cellspacing="10" style="background-color:rgba(255,255,255,0.75); width:50%">
<tr>
<td colspan=2><h2 align = "center">SignUp</h1></td>
</tr>
<tr>
<td>Username</td>
<td align="center" style="color:red"><input type = "text" name = "username" size="35" value = "<?php echo $username;?>" />
*<br/> <?php echo $usernameErr;?></td>
</tr>
<tr>
<td>Name</td>
<td align="center" style="color:red"><input type = "text" name = "name" size="35" value = "<?php echo $name;?>" />
*<br/><?php echo $nameErr;?></td>
</tr>
<tr>
<td>Password</td>
<td align="center" style="color:red"><input type = "password" name = "pass" size="35" value = "<?php echo $pass;?>"/>
*<br/><?php echo $passErr;?></td>
</tr>
<tr>
<td>Confirm Password</td>
<td align="center" style="color:red"><input type = "password" name = "cpass" size="35" value = "<?php echo $cpass;?>"/>
*<br/><?php echo $cpassErr;?></td>
</tr>
<tr>
<td>Email</td>
<td align="center" style="color:red"><input type = "email" name = "email" size="35" value = "<?php echo $email;?>"/>
*<br/><?php echo $emailErr;?></td>
</tr>
<tr>
<td>Contact No</td>
<td align="center" style="color:red"><input type = "text" name = "contact" size="35"value = "<?php echo $contact;?>"/><br>
<?php echo $contactErr;?></td>
</tr>
<tr>
<td colspan = 3 align = "center"><input type = "submit" name = "submit" value = "Register" />/ <a href = "index.php">Already Member?</a></td>
</tr>
</table>
</form>
</body>
</html>