<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Recover Password</title>
</head>
<?php require_once 'header.php'?>
<form method = "POST" action = "controller/recover.php">
<table align="center" cellpadding="10" style="background-color:rgba(255,255,255,0.75); width:30%">
<tr>
<td align="center"><h2>RECOVER PASSWORD</h1></td>
</tr>
<tr>
<td align="center"><input type = "text" name = "username" size="30" value = "<?php if(isset($_SESSION['recover'])) { echo $_SESSION['recover']['username']; }?>" placeholder="Username"></td>
</tr>
<tr>
<td align="center"><input type = "email" name = "email" size="30" value = "<?php if(isset($_SESSION['recover'])) { echo $_SESSION['recover']['email']; } ?>" placeholder="Email"></td>
</tr>
<tr><td align="center" style="color:red"><?php if(isset($_SESSION['recover'])) { echo $_SESSION['recover']['msg']; } ?></td></tr>
<td colspan = 2 align = "center">
<input type = "submit" name = "submit" value = "Send new password to email" />
</td>
</tr>
</table>
</form>
</body>
<?php
unset($_SESSION['recover']);
?>
</html>