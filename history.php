<?php
  include 'header.php';
  require_once 'core.inc.php';
    $idd="";
    $count=0;
  
  if($_SERVER['REQUEST_METHOD']="POST"){
  require_once 'dbconnect.inc.php';
   $username=$_SESSION['user'];
   
  echo  "<h2>HISTORY OF POSTS ADDED BY YOU</h2>";
  
   $query="SELECT  `ID`,`Title`, `Subject`, `Author`, `Edition`, `Original_price`, `Selling_price`,`dateofpost`,`Photo_Link`,`sold` FROM `posts` WHERE `Username`='".$username."' "; 
  
  if($query_run =mysql_query($query)){
	//  if(mysql_fetch_assoc($query_run)!=NULL)
	
   	 echo "<table border='1' style='width:100%'><tr><th>ID</th><th>Title</th><th>Subject</th><th>Author</th><th>Edition</th><th>Original Price</th><th>Selling Price</th><th>Date Of Post</th><th>Picture of book</th><th>Status(sold/unsold)</th></tr>";
	  
     while($query_row=mysql_fetch_assoc($query_run))
	 {
     $count=$count+1;
	 $id=$query_row['ID'];
    global $id;
	  $title=$query_row['Title'];
      $subject=$query_row['Subject'];
      $author=$query_row['Author'];
      $edition=$query_row['Edition'];
      $original_price=$query_row['Original_price'];
      $selling_price=$query_row['Selling_price'];
	  $date=$query_row['dateofpost'];
	 //  header("Content-type: image/jpeg");
	  $photo=$query_row['Photo_Link'];
	  $sold=$query_row['sold'];
      echo "<tr><td>".$id."</td><td>".$title."</td> <td>" .$subject. "</td><td>".$author."</td><td>".$edition."</td><td>".$original_price."</td><td>".$selling_price."</td><td>".$date."</td><td>".$photo."</td><td>".$sold."</td></tr>";
	  
	 }
     echo "</table>";
    echo "<tr>No. of books pooks posted by " .$username."=" .$count."</tr>";
     }
    else 
     {
     echo mysql_error();
      }
            
	      $idd=$_POST['idd'];
		  if(isset($_POST['submit'])){
		  $query="SELECT `ID` FROM `posts` WHERE `Username`='".$username."'";
	     	if($query_run=mysql_query($query)){
			while($query_row=mysql_fetch_assoc($query_run))
             { 
				 $id=$query_row['ID'];
                   if($id==$idd)
				 {
                      header('Location: editpost1.php');
                     
				 }
			 }
  
		  }
		  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>History</title>
</head>
<body>

<form input="name" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> " method="post">
<table>
If you want to edit your post Type the id you want to edit
<tr>
<td><input type="text" name="idd" value="<?php echo $idd ?> "></td>
</tr> 

<tr><td><input type="submit" name="submit" value="SUBMIT" ></td></tr> 
</table>
<p align="right">
   <a href = "profile.inc.php" ><i> Go to your profile</i> </center></a>
</p>


</body>
</html>
