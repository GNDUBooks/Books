<?php
require 'core.inc.php';
$username = $name = $email = $pass = $cpass = $contact = "";
$usernameErr = $nameErr = $emailErr = $passErr = $cpassErr = $contactErr = "";
$flag = true;

if(loggedin()){
	echo 'Already Registered and Logged In';
	header('Location: index.php');
} else { 
	if(isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] == "POST"){
		
		if (empty($_POST["username"])) {
			$usernameErr = "Username is required";
			$flag = false;
		} else {
			$username = test_input($_POST["username"]);
			// check if username only contains letters and numbers
			if (!preg_match("/^[a-zA-Z0-9]{6,30}$/",$username)) {
				$usernameErr = "Only letters and numbers allowed and length between 6 and 30";
			} else {
				$flag = false;
			}
		}

		if (empty($_POST["name"])) {
			$nameErr = "Name is required";
			$flag = false;
		} else {
			$name = test_input($_POST["name"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z ]{3,30}$/",$name)) {
				$nameErr = "Only letters and white space allowed and length between 3 and 30";
			} else {
				$flag = false;
			}
		}
	  
		if (empty($_POST["pass"])) {
			$passErr = "Password is required";
			$flag = false;
		} else {
			$pass = test_input($_POST["pass"]);
			// check if password only contains letters and numbers
			if (!preg_match("/^[a-zA-Z0-9]{6,20}$/",$pass)) {
				$passErr = "Only letters and numbers are allowed and length between 6 and 20";
			} else {
				$flag = false;
			}
		}

		if (empty($_POST["cpass"])) {
			$cpassErr = "Confirmation password is required";
			$flag = false;
		} else {
			$cpass = test_input($_POST["cpass"]);
			// check if password match
			if ($pass != $cpass) {
				$cpassErr = "Passwords must match";
			} else {
				$flag = false;
			}
		}
		  
		if (empty($_POST["email"])) {
			$emailErr = "Email is required";
			$flag = false;
		} else {
			$email = test_input($_POST["email"]);
			// check if e-mail address is well-formed
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format";
			} else if(strlen($email)>50){
				$emailErr = "Email length exceeds 50 character limit";
			} else {
				$flag = false;
			}
		}
		  
		if(!empty($_POST["contact"])) {
			$contact = test_input($_POST["contact"]);
			// check if contact only contains numbers
			if (!preg_match("/^[0-9]{10,12}$/",$contact)) {
				$contactErr = "Only numbers are allowed and length between 10-12";
			} else {
				$flag = false;
			}
		}
		
		if(!$flag){
			require_once 'dbconnect.php';
			
			$query = "select username from temp where username = '$username'";
			if($query_run = mysql_query($query)) {
				$query_num_rows = mysql_num_rows($query_run);
				if($query_num_rows != 0) {
					$usernameErr = 'Username already exists';
				} else {
					$flag = true;
					while($flag) {
						$otp = md5(rand(1,99999));
						$query = "select otp from confirmation where otp = '".$otp."'";
						if($query_run = mysql_query($query)) {
							$query_num_rows = mysql_num_rows($query_run);
							if($query_num_rows == 0) {
								$flag = false;
							} else {
								echo 'hehe';
							}
						}
					}
					$pass = md5($pass);
					$query1 = "insert into confirmation values ('$otp','$email','$pass')";
					$query2 = "insert into temp values ('$username','$name','$email','$contact','$otp')";
					if($query_run1 = mysql_query($query1) && $query_run2 = mysql_query($query2)){
						$redirect_page = "confirm.php";
						header('Location: '.$redirect_page);
					} else {
						echo 'retry after sometime as some error occured';
					}
					
				}
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
<td><input type = "password" name = "pass"  value = "<?php echo $pass;?>"/><span class="error">* <?php echo $passErr;?></span></td>
</tr>
<tr>
<td>Confirm Password</td>
<td><input type = "password" name = "cpass"  value = "<?php echo $cpass;?>"/><span class="error">* <?php echo $cpassErr;?></span></td>
</tr>
<tr>
<td>Email</td>
<td><input type = "text" name = "email"  value = "<?php echo $email;?>"/><span class="error">* <?php echo $emailErr;?></span></td>
</tr>
<tr>
<td>Contact No</td>
<td><input type = "text" name = "contact"  value = "<?php echo $contact;?>"/><span class="error"><?php echo $contactErr;?></span></td>
</tr>
<tr>
<td colspan = 2 align = "center"><input type = "submit" name = "submit" value = "Register" />/ <a href = "index.php">Already Member?</a></td>
</tr>
</table>
</form>
</body>
</html>