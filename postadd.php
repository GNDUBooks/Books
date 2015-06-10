<?php
session_start();
require_once 'controller/dbconnect.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>AD POST</title>
<script type="text/javascript">
    function updateMinMax(val) {
		var a = val;
		var min = Math.round(a / 3);
		min = min - (min % 10);
		var max = Math.round(a / 2);
		max = max - (max % 10);
		document.getElementById('sellprice').min= min;
		document.getElementById('sellprice').max= max;
		document.getElementById('sellprice').value= min;
	}
</script>
</head>
<?php
require_once 'header.php';
?>
<form input = "name" method = "post" action="controller/postadd.php" enctype="multipart/form-data" >
<table align="center" cellpadding=10 style="background-color:rgba(255,255,255,0.75); width:40%">
<tr>
<td colspan = 2 align = "center"><h2>POST AD</h2></td>
</tr>
<tr>
<td>BOOK NAME : </td>
<td align="center" style="color:red;"><input type = "text" name = "bookname" value = "<?php if(isset($_SESSION['postadd']['bookname'])) { echo $_SESSION['postadd']['bookname']; }?>" size=32><br>
<?php 
	if(isset($_SESSION['postadd']['booknameErr'])) {
		echo $_SESSION['postadd']['booknameErr'];
	}
?></td>
</tr>
<tr>
<td>AUTHOR NAME : </td>
<td align="center" style="color:red"><input type = "text" name = "author" value = "<?php if(isset($_SESSION['postadd']['author'])) { echo $_SESSION['postadd']['author']; }?>" size=32><br>
<?php
	if(isset($_SESSION['postadd']['authorErr'])) { 
		echo $_SESSION['postadd']['authorErr'];
	}
?></td>
</tr>
<tr>
<td>SUBJECT : </td>
<td align="center" style="color:red"><select name = "subject">
<option value = "0" 
<?php if(isset($_SESSION['postadd']['subject'])) {
	$subject = $_SESSION['postadd']['subject'];
	} else {
		$subject = "0";
	}
	if($subject == "0") {
		echo "selected = selected";
	}
?> >Select Subject</option>
<?php
$query = "select SubjectName from subject where SubjectName!='Other'";
if($queryrun = mysql_query($query)) {
	while($result = mysql_fetch_assoc($queryrun)){
		echo "<option value = \"".$result['SubjectName']."\"";
		if($subject == $result['SubjectName']){ echo "selected=selected"; }
		echo ">".$result['SubjectName']."</option>";
	}
} else {
	die(mysql_error());
}
?>
<option value = "Other" <?php if($subject == "Other") { echo "selected = selected";}?> >Other</option>
</select><br>
<?php 
	if(isset($_SESSION['postadd']['subjectErr'])) { 
		echo $_SESSION['postadd']['subjectErr']; 
	}
?></td>
</tr>
<tr>
<td>EDITION : </td>
<td align="center" style="color:red"><input type = "number" name = "edition" value = "<?php if(isset($_SESSION['postadd']['edition'])) { echo $_SESSION['postadd']['edition']; }?>" min="1" max="50" size=32><br>
<?php
	if(isset($_SESSION['postadd']['editionErr'])) {
		echo $_SESSION['postadd']['editionErr'];
	}
?></td>
</tr>
<tr>
<td>ORIGINAL PRICE : </td>
<td align="center" style="color:red"><input type = "number" id="origprice" name = "origprice" value = "<?php echo $origprice; ?>" size = "32" min="50" max="9999" step="10" onchange="updateMinMax(this.value);"><br>
<?phpif(isset($_SESSION['postadd']['origpriceErr'])) {
		echo $_SESSION['postadd']['origpriceErr'];
	}
?></td>
</tr>
<tr>
<td>SELLING PRICE : </td>
<td align="center" style="color:red"><input type = "number" id="sellprice" name = "sellprice" size = "32" min="<?php $min=50; echo $v = round(($min/3),-1);?>" max="<?php $max=50; echo round(($max/2),-1);?>" value="<?php echo $sellprice; ?>" step="10"><br>
<?phpif(isset($_SESSION['postadd']['sellpriceErr'])) {
		echo $_SESSION['postadd']['sellpriceErr'];
	}
?></td>
</tr>
<tr><td>Image : </td>
<td align="center" style="color:red"><input type = "file" name = "image" id = "fileToUpload"><br>
<?phpif(isset($_SESSION['postadd']['imageErr'])) {
		echo $_SESSION['postadd']['imageErr'];
	}
?></td>
</tr>
<tr>
<td colspan=2 align="center">
<?php
	if(isset($_SESSION['postadd']['error'])) {	
		echo $_SESSION['postadd']['error'];
	}
?>
</td>
</tr>
<tr>
<td colspan = 2 align = "center">
<input type = "submit" name = 'submit' value = "SUBMIT" </td>
</tr>
</table>
</body>
<?php
unset($_SESSION['postadd']);
?>
</html>