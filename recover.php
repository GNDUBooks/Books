<?php
$email = $username = '';
$usernameErr = $emailErr = ''; 
$flag = true;
require_once 'core.inc.php';
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
	$value = checkusername($_POST['username']);
	$username = $value['username'];
	$usernameErr = $value['usernameErr'];
	$flag = !$value['flag'];
	
	$value = checkemail($_POST['email']);
	$email = $value['email'];
	$emailErr = $value['emailErr'];
	$flag = !$value['flag'];
	
	if($flag){
		require
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
</tr>
<tr>
<td>Email: </td><td><input type = "text" name = "email" value = "<?php echo $email; ?>"></td>
</tr>
<tr>
<td colspan = 2 align = "center"><input type = "submit" value = "Submit" />

</td>
</tr>
</table>
</form>
</body>
</html>