<?php
require_once 'header.php';
$error = '';
if(isset($_POST['username']) && isset($_POST['pass'])){
	$username = strtolower(test_input($_POST['username']));
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
				header('Location: index.php');
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
<script type="text/javascript" src="js/message.js"></script>
<script type="text/javascript" src="js/login-hash.js"></script>
<style>
.message {
	padding: 20px;
	color:white;
	background-color:black;
	margin: 10px auto;
	width: 40%;
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
<form action = "<?php echo $current_file; ?>" method = "POST">
<div id="alert-message" class="message"></div>
<table align="center" cellpadding="10" style="background-color:rgba(255,255,255,0.75); width:30%">
<tr>
<td  align = "center"><h2>LOG IN</h1></td>
</tr>
<td align="center"><input type = "text" name = "username" size="30" placeholder="Username"/></td>
</tr>
<tr>
<td align="center"><input type = "password" name = "pass" size="30" placeholder="Password"/></td>
</tr>
<tr><td align="center" style="color:red">
<?php echo $error; ?></td></tr>
<tr>
<tr>
<td align = "center"><input type = "submit" value = "LogIn" /> / <a href = "SignUp.php">SignUp</a><br/>
<a href = "recover.php">Forgot Password?</a>
</td>
</tr>
</table>
</body>
</form>
</html>