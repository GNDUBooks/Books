<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Change Email</title>
	</head>
	<?php require_once 'header.php'; ?>
	<body>
		<form method = "POST" action = "controller/changemail.php">
			<table align="center" cellpadding=10 style="background-color:rgba(255,255,255,0.75); width:40%">
				<tr>
					<td colspan = 2 align = "center"><h2>CHANGE EMAIL</h2></td>
				</tr>
				<tr>
					<td>Current Email: </td>
					<td style="color:red" align="center"><input type = "email" name = "email" size="30" required> *<br> 
						<?php
							if(isset($_SESSION['changemail']['emailErr']) && $_SESSION['changemail']['emailErr'] != 'Email Already Registered') {
								echo $_SESSION['changemail']['emailErr'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Password: </td>
					<td style="color:red" align="center"><input type = "password" name = "pass" size="30" required> *<br>
						<?php
							if(isset($_SESSION['changemail']['passErr'])) {
								echo $_SESSION['changemail']['passErr']; 
							}
						?>
					</td>
				</tr>
				<tr>
					<td>New Email: </td>
					<td style="color:red" align="center"><input type = "email" name = "newemail" size="30" value = "<?php if(isset($_SESSION['changemail']['newmail'])) { echo $_SESSION['changemail']['newmail']; } ?>" required> *<br>
						<?php 
							if(isset($_SESSION['changemail']['newmailErr'])) {
								echo $_SESSION['changemail']['newmailErr']; 
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Confirm New Email: </td>
					<td style="color:red" align="center"><input type = "email" name = "conemail" size="30" required> *<br>
						<?php 
							if(isset($_SESSION['changemail']['conmailErr'])) {
								echo $_SESSION['changemail']['conmailErr'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td colspan=2 align="center" style="color:red">
						<?php
							if(isset($_SESSION['changemail']['error'])) {
								echo $_SESSION['changemail']['error'];
							}
							unset($_SESSION['changemail']);
						?>
					</td>
				</tr>
				<tr>
					<td colspan = 2 align = "center"><input type = "submit" name = "changeemail" value = "Change Email"></td>
				</tr>
			</table>
		</form>
	</body>
</html>