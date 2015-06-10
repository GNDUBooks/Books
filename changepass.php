<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
</head>
<?php require_once 'header.php'?>
<form method = "POST" action= "controller/changepass.php">
<table align="center" cellpadding="10" style="background-color:rgba(255,255,255,0.75); width:40%">
<tr>
<td colspan = 2 align = "center"><h2>CHANGE PASSWORD</h2></td>
</tr>
<tr>
<td>Current Password: </td>
<td style="color:red" align="center"><input type = "password" name = "curpass" size="30" required> *<br>
<?php
if(isset($_SESSION['changepass'])) {
	echo $_SESSION['changepass']['curpassErr'];
} ?></td>
</tr>
<tr>
<td>New Password: </td>
<td style="color:red" align="center"><input type = "password" name = "newpass" size="30" required> *<br>
<?php
if(isset($_SESSION['changepass'])) {
	echo $_SESSION['changepass']['newpassErr'];
} ?></td>
</tr>
<tr>
<td>Confirm Password</td>
<td style="color:red" align="center"><input type = "password" name = "conpass" size="30" required> *<br>
<?php
if(isset($_SESSION['changepass'])) {
	echo $_SESSION['changepass']['conpassErr'];
} 
unset($_SESSION['changepass']);
?></td>
</tr>
<tr>
<td colspan = 2 align = "center">
<input type = "submit" name = "changepass" value = "Change Password"></td>
</tr>
</table>
</form>
</body>
</html>