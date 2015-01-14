<!DOCTYPE>
<html>
<head>
<title>ADD POST</title>
</head>
<body>
<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'header.php';
	unset($_SESSION["id"]);
	$bookname = $author = $subject = $edition = $sellprice = $origprice = $image = "";
	$booknameErr = $authorErr = $subjectErr = $editionErr = $sellpriceErr = $origpriceErr = $imageErr = "";
	$flag1 = $flag2 = $flag3 = $flag4 = $flag5 = $flag6 = true;
	if(isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] == "POST") {
		require_once 'dbconnect.inc.php';
		
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
			$author= test_input($_POST["author"]);
			if (!preg_match("/^[a-zA-Z ]{2,30}$/", $author)) {
				$authorErr = "Only letters allowed and length between 2 and 30";
				$flag2 = false;
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
		if($_POST['edition'] == "0") {
			$editionErr= "edition of book is required";
			$flag4 = false;
		} else {
			$edition = test_input($_POST["edition"]);
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
			} else if($sellprice >= ($origprice / 2) || $sellprice <= ($origprice / 3)) {
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
				$check = getimagesize($_FILES["image"]["tmp_name"]);
				if($check !== false) {
					$imageErr = "File is an image - " . $check["mime"];
					$uploadOk = 1;
				} else {
					$imageErr = "File is not an image.";
					$uploadOk = 0;
				}
				
				// Check if file already exists
				/*if (file_exists($target_file)) {
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				}*/
				
				// Check file size
				if ($_FILES["image"]["size"] > 524288) {
					$imageErr = "Sorry, your file is too large.";
					$uploadOk = 0;
				}

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
						$query = "insert into posts (ID,Title,Subject,Author,Edition,Original_Price,Selling_Price,Photo,Username) values (".$id.",'".$bookname."','".$subject."','".$author."','".$edition."',".$origprice.",".$sellprice.",0,'".$_SESSION['user']."')";
						if($query_run = mysql_query($query)) {
							if (move_uploaded_file($_FILES["image"]["tmp_name"], 'posts/'.$id.'.jpg')) {
								mysql_query("update posts set Photo = 1 where ID = '".$id."'");
								header('Location: index.php');
							} else {
								echo 'Photo cannot be saved. But your book has been added to our database';
							}
						} else {
							echo 'not posted';
						}
					} else {
						echo 'Unable to post your ad.';
					}
				}
			} else {
				$imageErr = 'No photo selected';
			}
		}
	}
} else {
	header('Location: index.php');
}
?>
<form input = "name" method = "post" action= "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype = "multipart/form-data" >
<table>
<tr>
<td colspan = 2><h1 align = "center">ADD POST</h1></td>
</tr>
<tr>
<td>BOOK NAME : </td>
<td><input type = "text" name = "bookname" value = "<?php echo $bookname; ?>"></td>
<td><span class = "error" >* <?php echo $booknameErr; ?> </span></td>
</tr>
<tr>
<td>SUBJECT : </td>
<td><input type = "text" name = "subject" value = "<?php echo $subject; ?>"></td>
<td><span class ="error" >* <?php echo $subjectErr; ?> </span></td>
</tr>
<tr>
<td>AUTHOR NAME : </td>
<td><input type = "text" name = "author" value = "<?php echo $author; ?>"></td>
<td><span class = "error" >* <?php echo $authorErr; ?></span></td>
</tr>
<tr>
<td>EDITION : </td>
<td><select name="edition">
<option value = "0" <?php if($edition == 0) {echo "selected = selected";}?>>Select edition</option>
<option value = "1" <?php if($edition == 1) {echo "selected = selected";}?>>first edition</option>
<option value = "2" <?php if($edition == 2) {echo "selected = selected";}?>>second edition</option>
<option value = "3" <?php if($edition == 3) {echo "selected = selected";}?>>third edition</option>
<option value = "4" <?php if($edition == 4) {echo "selected = selected";}?>>fourth edition</option>
<option value = "5" <?php if($edition == 5) {echo "selected = selected";}?>>5th edition</option>
<option value = "6" <?php if($edition == 6) {echo "selected = selected";}?>>6th edition</option>
<option value = "7" <?php if($edition == 7) {echo "selected = selected";}?>>7th edition</option>
<option value = "8" <?php if($edition == 8) {echo "selected = selected";}?>>8th edition</option>
<option value = "9" <?php if($edition == 9) {echo "selected = selected";}?>>9th edition</option>
<option value = "10" <?php if($edition == 10) {echo "selected = selected";}?>>10th edition</option>
<option value = "11" <?php if($edition == 11) {echo "selected = selected";}?>>11th edition</option>
<option value = "12" <?php if($edition == 12) {echo "selected = selected";}?>>12th edition</option>
<option value = "13" <?php if($edition == 13) {echo "selected = selected";}?>>13th edition</option>
<option value = "14" <?php if($edition == 14) {echo "selected = selected";}?>>14th edition</option>
<option value = "15" <?php if($edition == 15) {echo "selected = selected";}?>>15th edition</option>
</select>
</td>
<td><span class = "error" >* <?php echo $editionErr; ?></span></td>
</tr>
<tr>
<td>ORIGINAL PRICE : </td>
<td><input type = "text" name = "origprice" value = "<?php echo $origprice; ?>"></td>
<td><span class = "error" >* <?php echo $origpriceErr; ?></span></td>
</tr>
<tr>
<td>SELLING PRICE : </td>
<td><input type = "text" name = "sellprice" value = "<?php echo $sellprice; ?>"> </td>
<td><span class = "error" >* <?php echo $sellpriceErr; ?></span></td>
</tr>
<tr><td>Select image of book to upload: </td>
<td><input type = "file" name = "image" id = "fileToUpload"></td>
<td><span class = "error">* <?php echo $imageErr;?></span></td>
</tr>
<tr>
<td colspan = 2 align = "center">
<input type = "submit" name = 'submit' value = "SUBMIT" </td>
</tr>
</table>
</body>
</html>