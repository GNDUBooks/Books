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
<?php
require_once 'header.php';
require_once 'controller/dbconnect.inc.php';
session_start();
?>
<h2>EDITING POSTS ADDED BY <?php echo $_SESSION['user']; ?></h2>
<form method = "POST" action = "controller/editposts.php">
<table style ="background-color:rgba(255,255,255,0.5); width:100%">
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
if(isset($_SESSION["id"])) {
	$string = "";
	foreach($_SESSION["id"] as $x) {
		$string = $string.$x." OR ID = ";
	}
	$string = substr($string, 0, strlen($string) - 9);
	$query = "select * from posts where ID = $string";
	if(!$query_run = mysql_query($query)) {
		header('Location: '.$redirect_page.'#unabledbaccess');
	}
}
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
		echo "<tr><td align = \"center\">$i</td>
		<td align = \"center\"><input type = \"text\" name = \"title[$i]\" value = \"$t\"></td>
		<td align = \"center\"><select name = \"subject[$i]\">";
		$query = "select SubjectName from subject where SubjectName!='Other'";
		if($queryrun = mysql_query($query)) {
			while($result = mysql_fetch_assoc($queryrun)){
				echo "<option value = \"".$result['SubjectName']."\"";
				if($s == $result['SubjectName']){ echo "selected=selected"; } 
				echo ">".$result['SubjectName']."</option>";
			}
		} else {
			die(mysql_error());
		}
		if(!isset($error[$i])) {
			$error[$i] = "";
		}
		echo "<option value = \"Other\""; if($s == "Other") { echo "selected = selected";} echo "\">Other</option>
		</select></td>
		<td align = \"center\"><input type = \"text\" name = \"author[$i]\" value = \"$a\"></td>
		<td align = \"center\"><input type = \"number\" name = \"edition[$i]\" value = \"$e\" min=\"1\" max=\"50\"></td>
		<td align = \"center\"><input type = \"number\" id=\"origprice$i\" name = \"origprice[$i]\" value = \"$o\" min=\"50\" max=\"9999\" step=\"10\" onchange=\"updateMinMax(this.value,$i);\">
		<td align = \"center\"><input type = \"number\" id=\"sellprice$i\" name = \"sellprice[$i]\" value = \"$se\" min=\"".round(($o/3),-1)."\" max=\"".round(($o/2),-1)."\" step=\"10\">
		<td align = \"center\">$d</td>
		<td align = \"center\"><a href=\"posts/".$i.".jpg\"><img src = \"posts/$i.jpg\" style = \"width:125px;height:auto\"></a></td>
		</td></tr>
		<tr><td></td><td colspan=6 style=\"color:red\">";
		if(isset($_SESSION['editposts']['error'][$i])) {
			echo $_SESSION['editposts']['error'][$i];
		}
		echo "</td></tr>";
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
<?php
if(isset($_SESSION['editposts'])) {
	unset($_SESSION['editposts']);
}
?>
</html>