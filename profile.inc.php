<?php
require_once 'core.inc.php';
if(loggedin()){
	nocaching();
        require_once 'header.php';
	require_once 'dbconnect.inc.php';
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
</head>
<body>
<table cellpadding =10 >
<tr>
<td colspan = 3 align = "center"><h1><?php echo $_SESSION['user']."'s" ;?> Profile</h1></td>
</tr>
<tr>
<td>Name: </td>
<td><?php echo $name; ?></td>
<td rowspan=5 valign = "top">
<img src="<?php echo $link; ?>" style="width:220px;height:220px" alt = "<?php echo $photoErr; ?>"></img><br />
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
<table cellspacing = 10 cellpadding = 10>
<tr>
<td align = "center"><a href = "search.php">Search</a></td>
<td align = "center"><a href = "postadd.php">Post Ads</a></td>
<td align = "center"><a href = "history.php">History</a></td>
</tr>
<tr>
<td align = "center"><a href = "editprofile.php">Edit Profile</a></td>
<td align = "center"><a href = "changemail.php">Change Email</a></td>
<td align = "center"><a href = "changepass.php">Change Password</a></td>
</tr>
<tr><td></td>
<td align = "center"><a href = "logout.php">Log out</a></td>
<td></td></tr>
</table>
</body>
</html>