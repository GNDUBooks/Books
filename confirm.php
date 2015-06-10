<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>CONFIRMATION PAGE</title>
<script type="text/javascript" src="js/message.js"></script>
<script type="text/javascript" src="js/confirm-hash.js"></script>
<style>
.message {
	padding: 20px;
	color:white;
	background-color:black;
	margin: 10px auto;
	width: 50%;
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
<?php
require_once 'header.php';
?>
<form action="controller/confirm.php" method="POST">
<div id="alert-message" class="message"></div>
<table align="center" cellpadding="10" style="background-color:rgba(255,255,255,0.75); width:40%">
<tr><td colspan=2 align="center"><h2>CONFIRMATION PAGE</h1></td></tr>
<tr><td>EMail: </td>
<td align = "right" style="color:red"><input type = "email" name="email" size = 40 value = "<?php if(isset($_SESSION['confirm']['email'])) { echo $_SESSION['confirm']['email']; }?>" /> *<br>
<?php
	if(isset($_SESSION['confirm']['emailErr'])) {
		echo $_SESSION['confirm']['emailErr'];
	}
?></td></tr>
<tr><td>OTP: </td>
<td align = "right" style="color:red"><input type = "text" name = "otp" size = 40/> *<br>
<?php
	if(isset($_SESSION['confirm']['otpErr'])) {
		echo $_SESSION['confirm']['otpErr'];
	}
?></td></tr>
<tr><td colspan=2 style="color:red">
<?php
	if(isset($_SESSION['confirm']['error'])) {
		echo $_SESSION['confirm']['error'];
	}
?></td></tr>
<tr><td colspan=2 align="center"><input type="submit" name="submit" value="CONFIRM" /></td></tr>
</table>
</form>
</body>
</html>