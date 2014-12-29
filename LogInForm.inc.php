<?php
require_once 'header.php';
$error = '';
if(isset($_POST['username']) && isset($_POST['pass'])){
	$username = test_input($_POST['username']);
	$pass = test_input($_POST['pass']);
	
	if(!empty($username) && !empty($pass)){
		$pass_hash = md5($pass);
		$query = "select Username from login where Username = '".mysql_real_escape_string($username)."' AND Password = '".mysql_real_escape_string($pass_hash)."'";
		if($query_run = mysql_query($query)){
			$query_num_rows = mysql_num_rows($query_run);
			if($query_num_rows == 0) {
				$error = 'Invalid username/password combination.';
			} else if($query_num_rows == 1) {
				$user = mysql_result($query_run,0,'Username');
				$_SESSION['user'] = $user;
				header('Location: '.$current_file);
			}
		}	
	} else {
		$error = 'You must supply a username and password.';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Log In</title>
</head>
<body>
<form action = "<?php echo $current_file; ?>" method = "POST">
<table>
<tr>
<td colspan = 2><h1 align = "center">LOG IN</h1></td>
</tr>
<tr>
<td>Username</td>
<td><input type = "text" name = "username" /></td>
</tr>
<tr>
<td>Password</td>
<td><input type = "password" name = "pass" /></td>
</tr>
<tr>
<td colspan = 2 align = "center"><input type = "submit" value = "LogIn" /> / <a href = "SignUp.php">SignUp</a><br/>
<a href = "recover.php">Forgot Password?</a>
</td>
</tr>
<?php echo $error; ?>
</table>
</body>
</form>
</html>