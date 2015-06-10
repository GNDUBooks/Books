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
	<?php require_once 'header.php';?>
		<form action = "controller/LogInForm.inc.php" method = "POST">
			<div id="alert-message" class="message"></div>
			<table align="center" cellpadding="10" style="background-color:rgba(255,255,255,0.75); width:30%">
				<tr>
					<td  align = "center"><h2>LOG IN</h1></td>
				</tr>
					<td align="center"><input type = "text" name = "username" size="30" placeholder="Username" required /></td>
				</tr>
				<tr>
					<td align="center"><input type = "password" name = "pass" size="30" placeholder="Password" required /></td>
				</tr>
				<tr>
					<td align="center" style="color:red">
					<?php if(isset($_SESSION['LogIn'])) { echo $_SESSION['LogIn']['error']; }?>
					</td>
				</tr>
				<tr>
					<td align = "center"><input type = "submit" value = "LogIn" /> / <a href = "SignUp.php">SignUp</a><br/>
					<a href = "recover.php">Forgot Password?</a>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
<?php
unset($_SESSION['LogIn']);
?>