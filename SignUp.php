<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			Sign Up
		</title>
	</head>
	<?php require_once 'header.php';?>
		<form method = "POST" action = "controller/signup.php">
			<table align="center" cellpadding="10" cellspacing="10" style="background-color:rgba(255,255,255,0.75); width:50%">
				<tr>
					<td colspan=2><h2 align = "center">SignUp</h1></td>
				</tr>
				<tr>
					<td>Username</td>
					<td align="center" style="color:red"><input type = "text" name = "username" size="35" value = "<?php
						if(isset($_SESSION['signup'])) {
							echo $_SESSION['signup']['username']; 
						}
						?>"  required /> *<br/> 
						<?php
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['usernameErr'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Password</td>
					<td align="center" style="color:red"><input type = "password" name = "pass" size="35"  required /> *<br/>
						<?php 
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['passErr'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td align="center" style="color:red"><input type = "password" name = "cpass" size="35"  required />*<br/>
						<?php 
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['cpassErr'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Name</td>
					<td align="center" style="color:red"><input type = "text" name = "name" size="35" value="<?php
						if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['name'];
							}
						?>" required /> *<br/>
						<?php 
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['nameErr'];
							}
						?>
					</td>
				</tr>
				</tr>
				<tr>
					<td>Email</td>
					<td align="center" style="color:red"><input type = "email" name = "email" size="35" value = "<?php
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['email'];
							}
						?>" required /> *<br/>
						<?php 
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['emailErr'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Contact No</td>
					<td align="center" style="color:red"><input type = "text" name = "contact" size="35"value = "<?php
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['contact'];
							}
						?>"/><br>
						<?php
							if(isset($_SESSION['signup'])) {
								echo $_SESSION['signup']['contactErr'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td colspan = 3 align = "center"><input type = "submit" name = "submit" value = "Register" />/ <a href = "index.php">Already Member?</a></td>
				</tr>
			</table>
		</form>
	</body>
	<?php
		if(isset($_SESSION['signup'])) {
			unset($_SESSION['signup']);	
		}
	?>
</html>