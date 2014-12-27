<?php
require_once 'core.inc.php';
$query_run = getuserdata('*','master','Username',$_SESSION['user']);
$name = $query_run['Name'];
$email = $query_run['Email'];
$contact = $query_run['ContactNo'];
$qual = $query_run['Qualification'];
$prof = $query_run['Profession'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
</head>
<body>
<table>
<tr>
<td colspan = 2 align = "center"><h1>PROFILE</h1></td>
</tr>
<tr>
<td>Name: </td>
<td><?php echo $name; ?></td>
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