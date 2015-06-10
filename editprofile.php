<html>
<head>
<title>Edit Profile</title>
</head>
<?php
require_once 'header.php';
require_once 'controller/core.inc.php';
require_once 'controller/dbconnect.inc.php';
if(!isset($_SESSION['editprofile'])) {
	$query_run = mysql_fetch_assoc(getuserdata('*','master','Username',$_SESSION['user']));
	$_SESSION['editprofile']['name'] = $query_run['Name'];
	$_SESSION['editprofile']['contact'] = $query_run['ContactNo'];
	$_SESSION['editprofile']['qual'] = $query_run['Qualification'];
	$_SESSION['editprofile']['prof'] = $query_run['Profession'];
}
if(is_file("pro_photos/".$_SESSION['user'].".jpg")) {
	$link = "pro_photos/".$_SESSION['user'].".jpg";
} else {
	$link = "pro_photos/"."edit.jpg";
}
?>
<form action="controller/editprofile.php" method="POST" enctype="multipart/form-data">
<table align="center" cellpadding=10 style="background-color:rgba(255,255,255,0.75); width:50%">
<tr>
<td colspan=3 align="center"><h2>
<?php
	echo $_SESSION['user']."'s";
?> Profile</h2></td>
</tr>
 <tr>
    <td>
	    Name:
	</td>	 
    <td style="color:red;">
    	<input type="text" name="name" size="30" value="<?php if(isset($_SESSION['editprofile'])){echo $_SESSION['editprofile']['name'];} ?>" ><br>
		<?php if(isset($_SESSION['editprofile']['nameErr'])) {echo $_SESSION['editprofile']['nameErr'];} ?><br>
	</td>
    <td rowspan=4 valign="top">
	<img src="<?php echo $link;?>" style="width:220px;height:auto" ></img><br /><br><br>
<?php
	if(isset($_SESSION['editprofile']['picErr'])) {
		echo $_SESSION['editprofile']['picErr']; 
	}
?>
<input type="file" name="files" id="Link_Photo" /><br>
<a href='controller/remove.php'>Remove Photo</a><br><br>
</td>
</tr>
<tr>
    <td>
       ContactNo: 
	</td>
    <td style="color:red;">
		<input type="text" name="contact" size="30" value="<?php if(isset($_SESSION['editprofile'])) {echo $_SESSION['editprofile']['contact'];} ?>" ><br>
		<?php if(isset($_SESSION['editprofile']['contactErr'])) {echo $_SESSION['editprofile']['contactErr'];} ?><br>
	</td>
</tr>
<tr>
    <td> 
		Qualification: 
	</td>
	<td style="color:red;">
	<input type="text" name="qualification" size="30" value="<?php if(isset($_SESSION['editprofile'])) {echo $_SESSION['editprofile']['qual'];} ?>" ><br>
	<?php if(isset($_SESSION['editprofile']['qualErr'])) {echo $_SESSION['editprofile']['qualErr'];} ?><br>
	</td>
</tr>
<tr>
    <td>
		Profession: 
	</td>
	<td align="left">
	<input type="text" name="profession" size="30" value="<?php if(isset($_SESSION['editprofile'])) {echo $_SESSION['editprofile']['prof'];} ?>" > <br>
	<?php if(isset($_SESSON['editprofile']['profErr'])) {echo $_SESSION['editprofile']['profErr'];} ?><br>
	</td>
</tr>
<tr><td colspan=3 align="center">
<input type="submit" name="edit" value="Update"></td>
</tr>
</form>
</body>
</html>
<?php 
	if(isset($_SESSION['editprofile'])) {
		unset($_SESSION['editprofile']);
	}
?>