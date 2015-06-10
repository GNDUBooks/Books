<?php
require_once 'controller/core.inc.php';
nocaching();
if(loggedin()){
	unset($_SESSION["search"]);
	require_once 'controller/dbconnect.inc.php';
	$photoErr = '';
	$query_result = mysql_fetch_assoc(getuserdata('*','master','Username',$_SESSION['user']));
	$name = $query_result['Name'];
	$email = $query_result['Email'];
	$contact = $query_result['ContactNo'];
	$qual = $query_result['Qualification'];
	$prof = $query_result['Profession'];	
	
	if($query_result['Link_Photo'] && is_file("pro_photos/".$_SESSION['user'].'.jpg') && filesize("pro_photos/".$_SESSION['user'].'.jpg') > 0) {
		$link = "pro_photos/".$_SESSION['user'].'.jpg';
	} else {
		$link = "pro_photos/"."edit.jpg";
	}
} else {
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<script type="text/javascript" src="js/message.js"></script>
<script type="text/javascript" src="js/profile-hash.js"></script>
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
<center>
<div id="alert-message" class="message"></div>
<table cellpadding =20 style="background-color:rgba(255,255,255,0.75); width:55%">

<tr>
<td colspan = 3 align = "center"><h2><?php echo $_SESSION['user']."'s" ;?> Profile</h2></td>
</tr>
<tr>

<td>Name: </td>
<td><?php echo $name; ?></td>
<td rowspan=5 valign = "top">
<img src="<?php echo $link; ?>" style="width:220px;height:auto" alt = "<?php echo $photoErr; ?>"></img><br />
</td>
</tr>
<tr>
<td>Email: </td>
<td><?php echo $email; ?></td>
</tr>
<tr>
<td>ContactNo: </td>
<td><?php echo $contact; ?></td>
</tr>
<tr>
<td>Qualification: </td>
<td><?php echo $qual; ?></td>
</tr>
<tr>
<td>Profession: </td>
<td><?php echo $prof; ?></td>
</tr>
</table>

<table cellspacing = 10 cellpadding = 10 style="background-color:rgba(255,255,255,0.75); width:55%">
<tr>
<td align = "center"><a href = "search.php">Search</a></td>
<td align = "center"><a href = "postadd.php">Post Ads</a></td>
<td align = "center"><a href = "history.php">History</a></td></tr>
<tr>
<td align = "center"><a href = "editprofile.php">Edit Profile</a></td>
<td align = "center"><a href = "changemail.php">Change Email</a></td>
<td align = "center"><a href = "changepass.php">Change Password</a></td></tr>
<tr><td></td><td align = "center"><a href = "controller/logout.php">Log out</a></td></tr>
</table>
</center>
</body>
</html>