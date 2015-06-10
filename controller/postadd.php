<?php
require_once 'core.inc.php';
if(loggedin()) {
	//require_once '../header.php';
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
									$destination_img = '../posts/'.$id.'.jpg';

									$d = compress($source_img, $destination_img, 50);
									
									header('Location: ../index.php'.'#adposted');
								} else {
									$error = mysql_error();
								}
							} else {
								header('Location: ../index.php'.'#notposted');
							}
						} else {
							$_SESSION['postadd']['bookname'] = $bookname;
							$_SESSION['postadd']['author'] = $author;
							$_SESSION['postadd']['subject'] = $subject;
							$_SESSION['postadd']['image'] = $image;
							$_SESSION['postadd']['imageErr'] = $imageErr;
							$_SESSION['postadd']['edition'] = $edition;
							header('Location: ../postadd.php');
						}
					} else {
						$imageErr = 'No photo selected';
						$_SESSION['postadd']['bookname'] = $bookname;
						$_SESSION['postadd']['author'] = $author;
						$_SESSION['postadd']['subject'] = $subject;
						$_SESSION['postadd']['image'] = $image;
						$_SESSION['postadd']['imageErr'] = $imageErr;
						$_SESSION['postadd']['edition'] = $edition;
						header('Location: ../postadd.php');
					}
				} else {
					$_SESSION['postadd']['bookname'] = $bookname;
					$_SESSION['postadd']['booknameErr'] = $booknameErr;
					$_SESSION['postadd']['author'] = $author;
					$_SESSION['postadd']['authorErr'] = $authorErr;
					$_SESSION['postadd']['subject'] = $subject;
					$_SESSION['postadd']['subjectErr'] = $subjectErr;
					$_SESSION['postadd']['image'] = $image;
					$_SESSION['postadd']['imageErr'] = $imageErr;
					$_SESSION['postadd']['edition'] = $edition;
					$_SESSION['postadd']['editionErr'] = $editionErr;
					$_SESSION['postadd']['sellpriceErr'] = $sellpriceErr;
					$_SESSION['postadd']['origpriceErr'] = $origpriceErr;
					header('Location: ../postadd.php');
				}
			}
		} else {
			$_SESSION['postadd']['error'] = "Your today's limit of posting 5 adds is over";
			$_SESSION['postadd']['bookname'] = $bookname;
			$_SESSION['postadd']['booknameErr'] = $booknameErr;
			$_SESSION['postadd']['author'] = $author;
			$_SESSION['postadd']['authorErr'] = $authorErr;
			$_SESSION['postadd']['subject'] = $subject;
			$_SESSION['postadd']['subjectErr'] = $subjectErr;
			$_SESSION['postadd']['image'] = $image;
			$_SESSION['postadd']['imageErr'] = $imageErr;
			$_SESSION['postadd']['edition'] = $edition;
			$_SESSION['postadd']['editionErr'] = $editionErr;
			$_SESSION['postadd']['sellpriceErr'] = $sellpriceErr;
			$_SESSION['postadd']['origpriceErr'] = $origpriceErr;
			header('Location: ../postadd.php');
		}
	} else {
		$_SESSION['postadd']['error'] = "Unable to process your request";
		$_SESSION['postadd']['bookname'] = $bookname;
		$_SESSION['postadd']['booknameErr'] = $booknameErr;
		$_SESSION['postadd']['author'] = $author;
		$_SESSION['postadd']['authorErr'] = $authorErr;
		$_SESSION['postadd']['subject'] = $subject;
		$_SESSION['postadd']['subjectErr'] = $subjectErr;
		$_SESSION['postadd']['image'] = $image;
		$_SESSION['postadd']['imageErr'] = $imageErr;
		$_SESSION['postadd']['edition'] = $edition;
		$_SESSION['postadd']['editionErr'] = $editionErr;
		$_SESSION['postadd']['sellpriceErr'] = $sellpriceErr;
		$_SESSION['postadd']['origpriceErr'] = $origpriceErr;
		header('Loaction: ../postadd.php');
	}
} else {
	header('Location: ../index.php');
}
?>