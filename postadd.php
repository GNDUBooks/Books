<!DOCTYPE html>
<html>
<head>
<title>ADD POST</title>
</head>
<body>
<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'header.php';
	$bookname = $author = $subject = $edition = $sellprice = $origprice = $image = "";
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
					if($edition < 1 || $edition > 50) {
						$editionErr = "Edition of book not valid";
						$flag4 = false;
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
<table>
<tr>
<td colspan = 2><h1 align = "center">ADD POST</h1></td>
</tr>
<tr>
<td>BOOK NAME : </td>
<td><input type = "text" name = "bookname" value = "<?php echo $bookname; ?>" size = "32"></td>
<td><span class = "error" >* <?php echo $booknameErr; ?> </span></td>
</tr>
<tr>
<td>SUBJECT : </td>
<td><select name = "subject">
<option value = "0" <?php if($subject == "0") { echo "selected = selected";}?>>Select Subject</option>
<option value = "Agriculture" <?php if($subject == "Agriculture") { echo "selected = selected";}?>>Agriculture</option>
<option value = "Architecture" <?php if($subject == "Architecture") { echo "selected = selected";}?>>Architecture</option>
<option value = "Arts" <?php if($subject == "Arts") { echo "selected = selected";}?>>Arts</option>
<option value = "Chemistry" <?php if($subject == "Chemistry") { echo "selected = selected";}?>>Chemistry</option>
<option value = "Commerce" <?php if($subject == "Commerce") { echo "selected = selected";}?>>Commerce</option>
<option value = "Computer Science" <?php if($subject == "Computer Science") { echo "selected = selected";}?>>Computer Science</option>
<option value = "Physics" <?php if($subject == "Physics") { echo "selected = selected";}?>>Engineering</option>
<option value = "Economics" <?php if($subject == "Economics") { echo "selected = selected";}?>>Economics</option>
<option value = "History" <?php if($subject == "History") { echo "selected = selected";}?>>History</option>
<option value = "Language" <?php if($subject == "Language") { echo "selected = selected";}?>>Language</option>
<option value = "Law" <?php if($subject == "Law") { echo "selected = selected";}?>>Law</option>
<option value = "Library Science" <?php if($subject == "Library Science") { echo "selected = selected";}?>>Library Science</option>
<option value = "Life Sciences" <?php if($subject == "Life Sciences") { echo "selected = selected";}?>>Life Sciences</option>
<option value = "Literature" <?php if($subject == "Literature") { echo "selected = selected";}?>>Literature</option>
<option value = "Management" <?php if($subject == "Management") { echo "selected = selected";}?>>Management</option>
<option value = "Mathematics" <?php if($subject == "Mathematics") { echo "selected = selected";}?>>Mathematics</option>
<option value = "Medicine and Health" <?php if($subject == "Medicine and Health") { echo "selected = selected";}?>>Medicine and Health</option>
<option value = "Philosophy and Pscychology" <?php if($subject == "Philosophy and Pscychology") { echo "selected = selected";}?>>Philosophy and Pscychology</option>
<option value = "Physics" <?php if($subject == "Physics") { echo "selected = selected";}?>>Physics</option>
<option value = "Political Science" <?php if($subject == "Political Science") { echo "selected = selected";}?>>Political Science</option>
<option value = "Religion" <?php if($subject == "Religion") { echo "selected = selected";}?>>Religion</option>
<option value = "Science" <?php if($subject == "Science") { echo "selected = selected";}?>>Science</option>
<option value = "Social Sciences and Sociology" <?php if($subject == "Social Sciences and Socialogy") { echo "selected = selected";}?>>Social Sciences and Socialogy</option>
</td>
<td><span class ="error" >* <?php echo $subjectErr; ?> </span></td>
</tr>
<tr>
<td>AUTHOR NAME : </td>
<td><input type = "text" name = "author" value = "<?php echo $author; ?>" size = "32"></td>
<td><span class = "error" >* <?php echo $authorErr; ?></span></td>
</tr>
<tr>
<td>EDITION : </td>
<td><input type = "number" name = "edition" value = "<?php echo $edition; ?>" size = "32"></td>
<td><span class = "error" >* <?php echo $editionErr; ?></span></td>
</tr>
<tr>
<td>ORIGINAL PRICE : </td>
<td><input type = "text" name = "origprice" value = "<?php echo $origprice; ?>" size = "32"></td>
<td><span class = "error" >* <?php echo $origpriceErr; ?></span></td>
</tr>
<tr>
<td>SELLING PRICE : </td>
<td><input type = "text" name = "sellprice" value = "<?php echo $sellprice; ?>" size = "32"> </td>
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