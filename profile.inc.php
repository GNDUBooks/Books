<?php
require_once 'core.inc.php';
$photoErr = '';
if(isset($_POST['change']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
	$photo_name = $_FILES['photo']['name'];
	$photo_type = $_FILES['photo']['type'];
	$photo_size = $_FILES['photo']['size'];
	$tmp_name = $_FILES['photo']['tmp_name'];
	$max_size = 524288;
	if(!empty($photo_name)){
		if(($photo_type == 'image/jpeg' || $photo_type == 'image/png') && $photo_size < $max_size){
			$location = 'pro_photos/';
			if(move_uploaded_file($tmp_name,$location.$_SESSION['user'].'.jpg')){
				require_once 'dbconnect.inc.php';
				$query = "update master set Link_Photo = 1 where Username = '".$_SESSION['user']."'";
				if(!mysql_query($query)){
					$photoErr = 'upload failed';
				}
			} else {
				$photoErr = 'Upload failed';
			}
		} else {
			$photoErr = 'jpg/jpeg/png allowed and size must be 512kb or less';
		}
	} else {
		$photoErr = 'No Photo selected';
	}
	
}
$query_run = getuserdata('*','master','Username',$_SESSION['user']);
$name = $query_run['Name'];
$email = $query_run['Email'];
$contact = $query_run['ContactNo'];
$qual = $query_run['Qualification'];
$prof = $query_run['Profession'];

$link = '';
if($query_run['Link_Photo']){
	$link = $_SESSION['user'].'.jpg';
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
</head>
<body>
<table cellpadding =10 border = 5>
<tr>
<td colspan = 3 align = "center"><h1>PROFILE</h1></td>
</tr>
<tr>
<td>Name: </td>
<td><?php echo $name; ?></td>
<td rowspan=5 valign = "top">
<img src="<?php echo 'pro_photos/'.$link ?>" style="width:100px;height:110px" alt = "<?php echo $photoErr; ?>"></img><br />
<?php echo $photoErr; ?>
<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST" enctype = "multipart/form-data">
<input type ="file" name = "photo"><br />
<input type = "submit" name = "change" value = "Change Photo">
</form>
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
<td align = "center"><a href = "search.php">Search </a></td>
<td align = "center"><a href = "post.php"> Post Ads </a></td>
<td align = "center"><a href = "history.php">History</a></td>
</tr>
<tr>
<td align = "center"><a href = "editprofile.php">Edit Profile</a></td>
<td align = "center"><a href = "changemail.php">Change Email</a></td>
<td align = "center"><a href = "changepass.php">Change Password</a></td>
</tr>
<tr><td></td>
<td align = "center"><a href = "logout.php">Log Out</a></td>
<td></td></tr>
</table>
</body>
</html>