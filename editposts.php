<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'header.php';
	require_once 'dbconnect.inc.php';
	$username = $_SESSION['user'];
	$flag = true;
	if(isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		$string = "";
		foreach($_SESSION["id"] as $x){
			if (empty($_POST["title"]["$x"]) || empty($_POST["subject"]["$x"]) || empty($_POST["author"]["$x"]) || empty($_POST["origprice"]["$x"]) || empty($_POST["sellprice"]["$x"])) {
				echo "All fields are required in postid $x";
				$flag = false;
				break;
			} else {
				$t = test_input($_POST["title"][$x]);
				$s = test_input($_POST["subject"][$x]);
				$a = test_input($_POST["author"][$x]);
				$e = test_input($_POST["edition"][$x]);
				$se = test_input($_POST["sellprice"][$x]);
				$o = test_input($_POST["origprice"][$x]);
				// check if name only contains letters and whitespace
				if (!preg_match("/^[a-zA-Z0-9 ]{1,75}$/",$t) || !preg_match("/^[a-zA-Z ]{2,30}$/",$s) || !preg_match("/^[a-zA-Z, ]{2,50}$/",$a) || !preg_match("/^[0-9]{1,3}$/",$e) || !preg_match("/^[0-9]{2,4}$/",$o) || !preg_match("/^[0-9]{2,4}$/",$se)) {
					echo "Format Violation in fields of $x<br>
					Only characters,whitespaces and digits are required in Title and length upto 75<br>
					Only characters and whitespaces are required in Subject and length upto 30<br>
					Only characters and whitespaces are required in Author and length upto 50<br>
					Only digits are required in Selling Price and length upto 4<br>
					Only digits are required in Original Price and length upto 4<br>";
					$flag = false;
				} else {
					$string = "update posts set Title = '".$t."',Subject = '".$s."', Author = '".$a."', Edition = ".$e.", Original_Price = ".$o.", Selling_Price = ".$se." where ID = ".$x.";";
					if(!mysql_query($string)) {
						die(mysql_error());
					}
					unset($_SESSION["id"][$x]);
				}
			}
		}
		if($flag) {
			header('Location: history.php');
		}
	}
	
	if(isset($_SESSION["id"])) {
		$string = "";
		foreach($_SESSION["id"] as $x) {
			$string = $string.$x." OR ID = ";
		}
		$string = substr($string, 0, strlen($string) - 9);
		$query = "select * from posts where ID = $string";
		if(!$query_run = mysql_query($query)) {
			die(mysql_error());
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
	$i = $query_row['ID'];
	if(!$query_row['sold']){
		$t = $query_row['Title'];
		$s = $query_row['Subject'];
		$a = $query_row['Author'];
		$e = $query_row['Edition'];
		$o = $query_row['Original_Price'];
		$se = $query_row['Selling_Price'];
		$d = $query_row['dateofpost'];
		//  header("Content-type: image/jpeg");
		$photo = $query_row['Photo'];
		echo "<tr><td align = \"center\">$i</td>
		<td align = \"center\"><input type = \"text\" name = \"title[$i]\" value = \"$t\"></td>
		<td align = \"center\"><select name = \"subject[$i]\">";
		$query = "select SubjectName from subject";
		if($queryrun = mysql_query($query)) {
			while($result = mysql_fetch_assoc($queryrun)){
				echo "<option value = \"".$result['SubjectName']."\"";
				if($s == $result['SubjectName']){ echo "selected=selected"; } 
				echo ">".$result['SubjectName']."</option>";
			}
		} else {
			die(mysql_error());
		}
		echo "<option value = \"Other\""; if($s == "Other") { echo "selected = selected";} echo "\">Other</option>
		</select></td>
		<td align = \"center\"><input type = \"text\" name = \"author[$i]\" value = \"$a\"></td>
		<td align = \"center\"><input type = \"number\" name = \"edition[$i]\" value = \"$e\"></td>
		<td align = \"center\"><input type = \"text\" name = \"origprice[$i]\" value = \"$o\">
		<td align = \"center\"><input type = \"text\" name = \"sellprice[$i]\" value = \"$se\">
		<td align = \"center\">$d</td>
		<td align = \"center\"><img src = \"posts/$i.jpg\" style = \"width:125px;height:150px\"></td>
		</td></tr>";
	} else {
		unset($_SESSION["id"][$i]);
	}
}
echo "</table><br/>";
?>
* Sold Books cannot be edited.
<input type = "submit" name = "save" value = "SAVE">
</form>
<p align="right">
   <a href = "profile.inc.php" ><i>Go to your profile</i> </center></a>
</p>
</body>
</html>