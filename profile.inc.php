<?php
require_once 'core.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
</head>
<body>
<table>
<tr>
<td colspan = 2 align = "center"><h1>Profile</h1></td>
</tr>
<tr>
<td>Name: </td>
<td><?php echo getuserfield('Name')?></td>
</tr>
<tr>
<td>Email: </td>
<td><?php echo getuserfield('Email')?></td>
</tr>
<tr>
<td>ContactNo: </td>
<td><?php echo getuserfield('ContactNo')?></td>
</tr>
<tr>
<td>Qualification: </td>
<td><?php echo getuserfield('Qualification')?></td>
</tr>
<tr>
<td>Profession: </td>
<td><?php echo getuserfield('Profession')?></td>
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