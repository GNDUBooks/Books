<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'header.php';
	require_once 'dbconnect.inc.php';
	$username = $_SESSION['user'];

	if(isset($_SESSION["id"])) {
		$string = "";
		foreach($_SESSION["id"] as $x) {
			$string = $string.$x." OR ID = ";
		}
		$string = substr($string, 0, strlen($string) - 9);
		echo $query = "select * from posts where ID = $string AND sold = 0";
		if(!$query_run = mysql_query($query)) {
			mysql_error();
		}
	}
} else {
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Posts</title>
</head>
<body>
<h2>EDITING POSTS ADDED BY <?php echo $username; ?></h2>
<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<table border = '1' style = 'width:100%'>
<tr>
<th>ID</th>
<th>Title</th>
<th>Subject</th>
<th>Author</th>
<th>Edition</th>
<th>Original Price</th>
<th>Selling Price</th>
<th>Date Of Post</th>
<th>Picture of book</th>
</tr>
<?php
while($query_row = mysql_fetch_assoc($query_run)) {
	$id = $query_row['ID'];
	$title = $query_row['Title'];
	$subject = $query_row['Subject'];
	$author = $query_row['Author'];
	$edition = $query_row['Edition'];
	$original_price = $query_row['Original_Price'];
	$selling_price = $query_row['Selling_Price'];
	$date = $query_row['dateofpost'];
	//  header("Content-type: image/jpeg");
	$photo = $query_row['Photo'];
	echo "<tr><td align = \"center\">$id</td>
	<td align = \"center\"><input type = \"text\" name = \"title\" value = \"$title\"></td>
	<td align = \"center\"><input type = \"text\" name = \"subject\" value = \"$subject\"></td>
	<td align = \"center\"><input type = \"text\" name = \"author\" value = \"$author\"></td>
	<td align = \"center\"><input type = \"text\" name = \"edition\" value = \"$edition\"></td>
	<td align = \"center\"><input type = \"text\" name = \"origprice\" value = \"$original_price\"></td>
	<td align = \"center\"><input type = \"text\" name = \"sellprice\" value = \"$selling_price\"></td>
	<td align = \"center\">".$date."</td>
	<td align = \"center\"><img src = \"posts/".$id.".jpg\" style = \"width:125px;height:150px\"></td>
	</td></tr>";
}
echo "</table><br/>";
?>
<input type = "submit" name = "save" value = "SAVE">
</form>
<p align="right">
   <a href = "profile.inc.php" ><i>Go to your profile</i> </center></a>
</p>
</body>
</html>