<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'header.php';
	require_once 'dbconnect.inc.php';
	unset($_SESSION['id']);
	$idd = "";
	$username = $_SESSION['user'];
	
	if((isset($_POST['sold']) || isset($_POST['delete'])) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST["check_list"])) {
			$string = "";
			foreach($_POST["check_list"] as $x) {
				$string = $string.$x." OR ID = ";
			}
			$string = substr($string, 0, strlen($string) - 9);
			if(isset($_POST['sold'])) {
				$query = "update posts set sold = 1 where ID = $string";
			} else if(isset($_POST['delete'])) {
				$query = "delete from posts where ID = $string";
			}
			if(!mysql_query($query)) {
				echo 'unable to process your request';
			}
		}
	}
	
	if(isset($_POST['edit']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST["check_list"])) {
			foreach($_POST["check_list"] as $x) {
				$_SESSION["id"][$x] = $x;
				header('Location: editposts.php');
			}
		}
	}
	
	$query_run = getuserdata('*','posts','Username',$username);
	if($query_run != 0) {
		$count = mysql_num_rows($query_run);
	} else {
		echo mysql_error();
	}
	
} else {
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>History</title>
</head>
<body>

<h2>HISTORY OF POSTS ADDED BY <?php echo $username; ?></h2>
No. of books posted by <?php echo $username;?> =  <?php echo $count;?>
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
<th>Status(sold/unsold)</th>
</tr>
<?php
if($count != 0) {
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
		$sold = $query_row['sold'];
		echo "<tr>
		<td align = \"center\"><input type = \"checkbox\" name = \"check_list[]\" value = \"$id\">".$id."</td>";
		echo "<td align = \"center\">".$title."</td>
		<td align = \"center\">" .$subject. "</td>
		<td align = \"center\">".$author."</td>
		<td align = \"center\">".$edition."</td>
		<td align = \"center\">".$original_price."</td>
		<td align = \"center\">".$selling_price."</td>
		<td align = \"center\">".$date."</td>
		<td align = \"center\"><img src = \"posts/".$id.".jpg\" style = \"width:125px;height:150px\"></td>
		<td align = \"center\">";
		if($sold == 1) {
			echo "Sold";
		} else {
			echo "Unsold";
		}
		echo "</td></tr>";
	}
	echo "</table><br/>
	<input type = \"submit\" name = \"sold\" value = \"Marked have been Sold\">
	<input type = \"submit\" name = \"delete\" value = \"Delete Selected\">
	<input type = \"submit\" name = \"edit\" value = \"Edit Selected\">";
}
?>

</form>
<p align="right">
   <a href = "profile.inc.php" ><i> Go to your profile</i> </center></a>
</p>

</body>
</html>