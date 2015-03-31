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
<body>
<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'header.php';
	$bookname = $author = $subject = $image = "";
	$edition = 1;
	//$sellprice = 50;
	$booknameErr = $authorErr = $subjectErr = $editionErr = $sellpriceErr = $origpriceErr = $imageErr = "";
	$flag1 = $flag2 = $flag3 = $flag4 = $flag5 = $flag6 = true;
	require_once 'dbconnect.inc.php';
	$date = date("Y-m-d");
	$query = "select count(ID) as 'no' from posts where Username = '".$_SESSION['user']."' and dateofpost like '".$date."_________'";
	if($query_run = mysql_query($query)){
		if(mysql_result($query_run,0,'no')<5){
			unset($_SESSION["id"]);
			if(isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] == "POST") {
				if(empty($_POST["bookname"])) {
					$booknameErr = "name of book is required";
					$flag1 = false;
				} else {
					$bookname = test_input($_POST["bookname"]);
					if (!preg_match("/^[a-zA-Z0-9 ]{2,50}$/", $bookname)) {
						$booknameErr = "Only letters and numbers allowed and length between 2 and 50";
						$flag1 = false;
					}
				}

				if(empty($_POST["author"])) {
					$authorErr = "name of the author of book is required";
					$flag2 = false;
				} else {
					$author = test_input($_POST["author"]);
					if (!preg_match("/^[a-zA-Z ]{2,30}$/", $author)) {
						$authorErr = "Only letters allowed and length between 2 and 30";
						$flag2 = false;
					}
				}
				
				if(empty($_POST["edition"])){
					$editionErr = "Edition Of Book is required";
					$flag4 = false;
				} else {
					$edition = test_input($_POST["edition"]);
					if(!preg_match("/^[1-9][0-9]{0,3}$/",$edition)) {
						$editionErr = "Only digits are allowed";
						$flag4 = false;
					}
				}
				
				if(empty($_POST["subject"])) {
					$subjectErr= "subject of book is required";
					$flag3 = false;
				} else {
					$subject= test_input($_POST["subject"]);
					if (!preg_match("/^[a-zA-Z ]{2,30}$/", $subject)) {
						$subjectErr = "Only letters allowed and length between 2 and 30";
						$flag3 = false;
					}
				}
				
				if(empty($_POST["origprice"])) {
					$origpriceErr= "Original price of book is required";
					$flag6 = false;
				} else {
					$origprice = test_input($_POST["origprice"]);
					if(!preg_match("/^[0-9]{2,4}$/",$origprice)){
						$origpriceErr="Only Numbers are allowed and length between 2-4";
						$flag6 = false;
					}
				}
				
				if(empty($_POST["sellprice"])) {
					$sellpriceErr= "selling price of book is required";
					$flag5 = false;
				} else {
					$sellprice= test_input($_POST["sellprice"]);
					if (!preg_match("/^[0-9]{2,4}$/", $sellprice)) {
						$sellpriceErr = "Only numbers are allowed.";
						$flag5 = false;
					} else if($sellprice >= (($origprice / 2) + 10) || $sellprice <= (($origprice / 3) - 10)) {
						$sellpriceErr = "selling price must lie between one third of original and half of original price";
						$flag5 = false;
					}
				}
				
				if($flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6) {
					
					if(!empty($_FILES["image"]["name"])) {
						$target_file = $_FILES["image"]["name"];
						$uploadOk = 1;
						$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

						// Check if image file is a actual image or fake image
					/*	$check = getimagesize($_FILES["image"]["tmp_name"]);
						if($check !== false) {
							$imageErr = "File is an image - " . $check["mime"];
							$uploadOk = 1;
						} else {
							$imageErr = "File is not an image.";
							$uploadOk = 0;
						}*/
						
						// Check if file already exists
						/*if (file_exists($target_file)) {
							echo "Sorry, file already exists.";
							$uploadOk = 0;
						}*/
						
						// Check file size
						/*if ($_FILES["image"]["size"] > 524288) {
							$imageErr = "Sorry, your file is too large.";
							$uploadOk = 0;
						}*/
						
						// Allow certain file formats
						if(!($imageFileType != "jpg" || $imageFileType != "png" || $imageFileType != "jpeg" || $imageFileType != "gif" )) {
							$imageErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
							$uploadOk = 0;
						}

						// Check if $uploadOk is set to 0 by an error
						if (!$uploadOk == 0) {
						// if everything is ok, try to upload file
							$query1 = "select max(id)+1 as id from posts"; 
							if($query_run = mysql_query($query1)){
								echo $id = mysql_result($query_run,0,'id');
								$query = "insert into posts (ID,Title,Subject,Author,Edition,Original_Price,Selling_Price,Username) values (".$id.",'".$bookname."','".$subject."','".$author."','".$edition."',".$origprice.",".$sellprice.",'".$_SESSION['user']."')";
								if($query_run = mysql_query($query)) {
									require_once 'upload.inc.php';
									$source_img = $_FILES['image']['tmp_name'];
									$destination_img = 'posts/'.$id.'.jpg';

									$d = compress($source_img, $destination_img, 50);
									
									header('Location: index.php'.'#adposted');
								} else {
									echo mysql_error();
								}
							} else {
								header('Location: index.php'.'#notposted');
							}
						}
					} else {
						$imageErr = 'No photo selected';
					}
				}
			} else {
				$origprice = 50;
				$sellprice = 20;
			}
		} else {
			echo "Your today's limit of posting 5 adds is over";
		}
	} else {
		echo mysql_error();
		echo "Unable to process your request";
	}
} else {
	header('Location: index.php');
}
?>
<form input = "name" method = "post" action= "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype = "multipart/form-data" >
<table align="center" cellpadding=10 style="background-color:rgba(255,255,255,0.75); width:40%">
<tr>
<td colspan = 2 align = "center"><h2>POST AD</h2></td>
</tr>
<tr>
<td>BOOK NAME : </td>
<td align="center" style="color:red;"><input type = "text" name = "bookname" value = "<?php echo $bookname; ?>" size=32><br>
<?php echo $booknameErr; ?></td>
</tr>
<tr>
<td>AUTHOR NAME : </td>
<td align="center" style="color:red"><input type = "text" name = "author" value = "<?php echo $author; ?>" size=32><br>
<?php echo $authorErr; ?></td>
</tr>
<tr>
<td>SUBJECT : </td>
<td align="center" style="color:red"><select name = "subject">
<option value = "0" <?php if($subject == "0") { echo "selected = selected";}?> >Select Subject</option>
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
<?php echo $subjectErr; ?></td>
</tr>
<tr>
<td>EDITION : </td>
<td align="center" style="color:red"><input type = "number" name = "edition" value = "<?php echo $edition; ?>" min="1" max="50" size=32><br>
<?php echo $editionErr; ?></td>
</tr>
<tr>
<td>ORIGINAL PRICE : </td>
<td align="center" style="color:red"><input type = "number" id="origprice" name = "origprice" value = "<?php echo $origprice; ?>" size = "32" min="50" max="9999" step="10" onchange="updateMinMax(this.value);"><br>
<?php echo $origpriceErr; ?></td>
</tr>
<tr>
<td>SELLING PRICE : </td>
<td align="center" style="color:red"><input type = "number" id="sellprice" name = "sellprice" size = "32" min="<?php $min=50; echo $v = round(($min/3),-1);?>" max="<?php $max=50; echo round(($max/2),-1);?>" value="<?php echo $sellprice; ?>" step="10"><br>
<?php echo $sellpriceErr; ?></td>
</tr>
<tr><td>Image : </td>
<td align="center" style="color:red"><input type = "file" name = "image" id = "fileToUpload"><br>
<?php echo $imageErr;?></td>
</tr>
<tr>
<td colspan = 2 align = "center">
<input type = "submit" name = 'submit' value = "SUBMIT" </td>
</tr>
</table>
</body>
</html>