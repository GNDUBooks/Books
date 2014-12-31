
<?php
include "header.php";
 require_once 'dbconnect.inc.php';
require_once 'core.inc.php';
$picerr='';
$path="pro_book_pic/";

$bookname=$author=$subject=$edition=$sellprice=$origprice=$image="";	
$booknameErr=$authorErr=$subjectErr=$editionErr=$sellpriceErr=$origpriceErr="";	
if( (isset($_POST['submit']) && !empty($_POST['submit'])) &&  $_SERVER['REQUEST_METHOD'] == 'POST')
  {

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
  

    if($_POST['edition']=="0" )
   {
     $editionErr= "edition of book is required";
    }
     else{
	$edition = test_input($_POST["edition"]);
    }

	if(empty($_POST["sellprice"])) {
		$sellpriceErr= "selling price of book is required";
		$flag4 = false;
	} else {
		$sellprice= test_input($_POST["sellprice"]);
		if (!preg_match("/^[0-9]{2,4}$/", $sellprice)) {
			$sellpriceErr = "Only numbers are allowed.";
			$flag4 = false;
		}
	}
	
	
	if(empty($_POST["origprice"])) {
		$origpriceErr= "Original price of book is required";
		$flag5 = false;
	} else {
		$origprice = test_input($_POST["origprice"]);
		if(!preg_match("/^[0-9]{2,4}$/",$origprice)){
			$origpriceErr="Only Numbers are allowed and length between 2-4";
			$flag5 = false;
		}

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

  

     

  
   
$username=$_SESSION['user'];
//echo isset($_POST['image']);
//echo empty($_POST['image']);
if(  empty($_POST['image']) )
  {
 $picname= $_FILES['image']['name'];
    $pictype = $_FILES['image']['type'];
    $picsize = $_FILES['image']['size'];
    if(!empty($picname))
    {
      $link=$path.$picname;
	  echo $link;
      if(($pictype== 'image/jpeg' || $pictype == 'image/png') && $picsize < GW_MAXFILESIZE)
      {
       if(move_uploaded_file($_FILES['image']['tmp_name'], $link))
       { 		 
		 if($bookname!="" && $author!="" && $subject!="" && $edition!="" && $sellprice!="" && $origprice!="" ){
          $query="Insert into posts(ID,Title,Subject,Author,Edition,Original_Price,Selling_Price,Photo_Link, Username, status) values ( '0' , '$bookname' ,'$subject', '$author', '$edition', '$origprice', '$sellprice', '$link', '$username', '0')";
          echo $query;
		  if($query_run=mysql_query($query))
	      { 
		   echo "query done";
		  }
           else{
			   echo "can not be posted";}
	  }
      }
      else echo "Some error occured while uploading. Please try again!";
    }
	else echo $picerr= "The picture should be of jpg or png format and 512kb or less!!";
  }else echo "Please insert an Image of your book for authentication!!"; }
}
?>



<!DOCTYPE>
<html>
<head>
<title>ADD POST</title> 
</head>

<body>
<form input="name" method="post" action= " <?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> " enctype="multipart/form-data" >
<table>
<tr>
<td colspan = 2><h1 align = "center">ADD POST</h1></td>
</tr>
<tr>
<td>* BOOK NAME : </td>
<td><input type="text" name="bookname" value="<?php echo $bookname; ?>"> </td>
<td>  <span class ="error" ><?php echo $booknameErr; ?> </span></td>  
</tr>

<tr>
<td>* SUBJECT : </td>
<td><input type="text" name="subject" value="<?php echo $subject ;?>"> </td> 
<td>  <span class ="error" > <?php echo $subjectErr; ?> </span></td>
</tr>

<tr>
<td>* AUTHOR NAME : </td>
<td><input type="text" name="author" value="<?php echo $author; ?>"> </td> 
<td>  <span class ="error" > <?php echo $authorErr; ?> </span></td>
</tr>
 
 <tr> 
<td>* EDITION : </td>
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
<td>  <span class ="error" > <?php echo $editionErr; ?> </span></td>
</tr>

<tr>
<td>* ORIGINAL PRICE : </td>
<td><input type="text" name="origprice" value="<?php echo "$origprice" ;?>"> </td> 
<td>  <span class ="error" > <?php echo $origpriceErr; ?> </span></td>
</tr>

<tr>
<td>* SELLING PRICE : </td>
<td><input type="text" name="sellprice" value="<?php echo "$sellprice" ;?>"> </td> 
<td>  <span class ="error" > <?php echo $sellpriceErr; ?> </span></td>
</tr>
<tr><?php if(empty($picname))
       $link="pro_book_pic/edit.jpg";?>
    <td rowspan=5 valign = "top">
    <img src="<?php echo $link ;?>" style="width:220px;height:220px" alt = "<?php echo $picerr; ?>"></img><br /><br><br>
    <?php echo $picerr; ?>
</tr><br>
<tr>
    <td>Select image of book to upload:<br>
    <input type="file" name="image" id="fileToUpload"> </td>
</tr>

<br><br>
<tr>
<td colspan = 2 align = "center">
<input type="submit" name="submit" value="SUBMIT" </td> 

<td colspan = 10 align = "right"><a href="profile.inc.php" >Exit</td></tr>


</table></form>
</body>
</html>
