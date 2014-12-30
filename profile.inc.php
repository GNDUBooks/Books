<?php
require ('dbconnect.inc.php');
require_once 'core.inc.php';
require_once 'header.php';

$photoErr = '';
$query_result = getuserdata('*','master','Username',$_SESSION['user']);
$name = $query_result['Name'];
$email = $query_result['Email'];
$contact = $query_result['ContactNo'];
$qual = $query_result['Qualification'];
$prof = $query_result['Profession'];	

if(is_file($query_result['Link_Photo']) && filesize($query_result['Link_Photo'])>0)
$link= $query_result['Link_Photo'];
else
$link=GW_UPLOADPATH."edit.jpg";


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
<td align = "center"><a href = "search.php">Search </a></td>
<td align = "center"><a href = "postadd.php"> Post Ads </a></td>
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
