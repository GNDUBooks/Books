<!DOCTYPE>
<html>
<head>
<title>ADD POST</title> 
</head>

<body>
<?php

require_once 'core.inc.php';
$bookname=$author=$subject=$edition=$sellprice=$origprice=$image="";	
$booknameErr=$authorErr=$subjectErr=$editionErr=$sellpriceErr=$origpriceErr="";	

if($_SERVER["REQUEST_METHOD"]=="POST")
{
 require_once 'dbconnect.inc.php';
	 if(empty($_POST["bookname"]))
	{
		 $booknameErr= "name of book is required";
	}
	else{
		$bookname= test_input($_POST["bookname"]);
     }
  if(empty($_POST["author"]))
	{
		 $authorErr= "name of the author of book is required";
	}
	else{
		$author= test_input($_POST["author"]);
     }

  if(empty($_POST["subject"]))
	{
		 $subjectErr= "subject of book is required";
	}
	else{
		$subject= test_input($_POST["subject"]);
     }
  
/*	if(  )
	{
		 $editionErr= "edition of book is required";
	}
*/	//else{
	$edition= test_input($_POST["edition"]);
     //}
  
	 if(empty($_POST["sellprice"]))
	{
		 $sellpriceErr= "selling price of book is required";
	}
	else
		$sellprice= test_input($_POST["sellprice"]);
		 if(!preg_match("/^[0-9]{1,4}$/",$sellprice)){
			$sellpriceErr="Only Numbers are allowed and length between 1-4";
			}
		
 if(empty($_POST["origprice"]))
	{
		 $origpriceErr= "Original price of book is required";
	}
	else
		$origprice= test_input($_POST["origprice"]);
		 if(!preg_match("/^[0-9]{1,4}$/",$origprice)){
			$origpriceErr="Only Numbers are allowed and length between 1-4";
			}

  
   $target_file =$_FILES["image"]["name"]; 
   $uploadOk = 1;
   $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
     $check = getimagesize($_FILES["image"]["tmp_name"]);
       if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["image"]["size"] > 50000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} //else {
  //  if (move_uploaded_file(, $target_file)) {
    //    echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
    //} else {
     //   echo "Sorry, there was an error uploading your file.";
    //}


       /*  
	    if(loggedin()){
		$query1="SELECT 'Username' FROM  'login'";  
         if( $query_run1=mysql_query($query1))
    	{
             $query_result=getuserdata1('Username','login');
		}
        }
   */
          $query="Insert into 'posts' values ( ' ' , " . mysql_real_escape_string ($bookname) ." ' , ' " . mysql_real_escape_string ($subject) . " '  , '".mysql_real_escape_string($author)."','".mysql_real_escape_string($edition)."','".mysql_real_escape_string($origprice)."','".mysql_real_escape_string($sellprice)."','','".$query_result['Username']."";
        
		  if($query_run=mysql_query($query))
	      { 
		     header('Location: logout.php');
		  }
           else{
			   echo "can not be posted";
			   }

}
 


?>

<form input="name" method="post" action= " <?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> " enctype="multipart/form-data" >
<table>
<tr>
<td colspan = 2><h1 align = "center">ADD POST</h1></td>
</tr>
<tr>
<td>BOOK NAME : </td>
<td><input type="text" name="bookname" value="<?php echo $bookname ?>"> </td>
<td>  <span class ="error" >* <?php echo $booknameErr; ?> </span></td>  
</tr>

<tr>
<td>SUBJECT : </td>
<td><input type="text" name="subject" value="<?php echo $subject ?>"> </td> 
<td>  <span class ="error" >* <?php echo $subjectErr; ?> </span></td>
</tr>

<tr>
<td>AUTHOR NAME : </td>
<td><input type="text" name="author" value="<?php echo $author ?>"> </td> 
<td>  <span class ="error" >* <?php echo $authorErr; ?> </span></td>
</tr>
 
 <tr> 
<td>EDITION : </td>
<td> <select name="edition">
<option value ="0">Select edition</option>
<option value ="1">first edition</option>
<option value ="2">second edition</option>
<option value ="3">third  edition</option>
<option value ="4">fourth edition</option>
<option value ="5">5th edition</option>
<option value ="6">6th edition</option>
<option value ="7">7th edition</option>
<option value ="8">8th edition</option><
<option value ="9">9th edition</option>
<option value ="10">10th edition</option>
<option value ="11">11th edition</option>
<option value ="12">12th edition</option>
<option value ="13">13th edition</option>
<option value ="14">14th edition</option>
<option value ="15">15th edition</option>

</select>

</td> 
<td>  <span class ="error" >* <?php echo $editionErr; ?> </span></td>
</tr>

<tr>
<td>ORIGINAL PRICE : </td>
<td><input type="text" name="sellprice" value="<?php echo "$origprice" ?>"> </td> 
<td>  <span class ="error" >* <?php echo $origpriceErr; ?> </span></td>
</tr>

<tr>
<td>SELLING PRICE : </td>
<td><input type="text" name="origprice" value="<?php echo "$sellprice" ?>"> </td> 
<td>  <span class ="error" >* <?php echo $sellpriceErr; ?> </span></td>
</tr>

<tr><td>Select image of book to upload:
    <input type="file" name="image" id="fileToUpload"> </td>
</tr>

<tr>
<td colspan = 2 align = "center">
<input type="submit"  value="SUBMIT" </td> 
</tr>

</table>
</body>
</html>
