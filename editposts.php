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
			$flag = $flag1 = $flag2 = $flag3 = $flag4 = $flag5 = $flag6 = $flag7 = $flag8 = $flag9 = true;
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
				if (!preg_match("/^[a-zA-Z0-9 ]{1,75}$/",$t)){
					echo "Only characters,whitespaces and digits are required in Title and length upto 75 in id ".$x."<br>";
					$flag1 = false;
				} 
				if (!preg_match("/^[a-zA-Z ]{2,40}$/",$s)){
					echo "Only characters and whitespaces are required in Subject and length upto 40 in id ".$x."<br>";
					$flag2 = false;
				} 
				if (!preg_match("/^[a-zA-Z, ]{2,50}$/",$a)){
					echo "Only characters and whitespaces are required in Author and length upto 50 in id ".$x."<br>";
					$flag3 = false;
				}
				if (!preg_match("/^[1-9][0-9]{0,1}$/",$e)){
					echo "Only numbers are required between 1 to 50 in edition in id ".$x."<br>";
					$flag4 = false;
				}
				if (($e > 50)){
					echo "edition of book should be less than 50 in id ".$x."<br>";
					$flag5 = false;
				}
				if (!preg_match("/^[1-9][0-9]{1,3}$/",$o)){
					echo "Only digits are required in Original Price and length upto 4 in id ".$x."	<br>";
					$flag6 = false;
				}
				if (!preg_match("/^[1-9][0-9]{1,3}$/",$se)){
					echo "Only digits are required in Selling Price and length upto 4 in id ".$x."<br>";
					$flag7 = false;
				}
				if ((($o < 50) || ($o > 9999))){
					echo "original price should be in range of 50 to 9999 in id ".$x."<br>";
					$flag8 = false;
				}
				if (($se > (($o/2) + 10)) || ($se < (($o/3) - 10))) {
					echo "Selling price should in range one third to one half of original price in id ".$x."<br>";
					$flag9 = false;
				}
				if($flag && $flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6 && $flag7 && $flag8 && $flag9){
					$string = "update posts set Title = '".$t."',Subject = '".$s."', Author = '".$a."', Edition = ".$e.", Original_Price = ".$o.", Selling_Price = ".$se." where ID = ".$x.";";
					if(!mysql_query($string)) {
						die(mysql_error());
					}
					unset($_SESSION["id"][$x]);
				} else {
					break;
				}
			}
		}
		if($flag && $flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6 && $flag7 && $flag8 && $flag9) {
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
<script type="text/javascript">
    function updateMinMax(val,i) {
		var a = val;
		var min = Math.round(a / 3);
		min = min - (min % 10);
		var max = Math.round(a / 2);
		max = max - (max % 10);
		document.getElementById('sellprice'+i).min= min;
		document.getElementById('sellprice'+i).max= max;
		document.getElementById('sellprice'+i).value= min;

	}
</script>
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
		<td align = \"center\"><input type = \"number\" name = \"edition[$i]\" value = \"$e\" min=\"1\" max=\"50\"></td>
		<td align = \"center\"><input type = \"number\" id=\"origprice$i\" name = \"origprice[$i]\" value = \"$o\" min=\"50\" max=\"9999\" step=\"10\" onchange=\"updateMinMax(this.value,$i);\">
		<td align = \"center\"><input type = \"number\" id=\"sellprice$i\" name = \"sellprice[$i]\" value = \"$se\" min=\"".round(($o/3),-1)."\" max=\"".round(($o/2),-1)."\" step=\"10\">
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