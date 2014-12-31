<?php

include ('dbconnect.inc.php');
include ('header.php');
include 'core.inc.php';

$picerr='';
 
$query_run = getuserdata('*','master','Username',$_SESSION['user']);
$name = $query_run['Name'];
$contact = $query_run['ContactNo'];
$qual = $query_run['Qualification'];
$prof = $query_run['Profession'];	

if(is_file($query_run['Link_Photo']) && filesize($query_run['Link_Photo'])>0)
{$link= $query_run['Link_Photo'];
}
else
$link=GW_UPLOADPATH."edit.jpg";
if( isset($_POST['edit']) && !empty($_POST['edit'] ) && empty($_POST['done']))
{
echo "working";
   if( empty($_POST['name']))
      { echo "Please fill the empty name field";}
	else  
      $name=test_input($_POST['name']); 
   if( empty($_POST['contact']))
	     echo "Please fill in the empty contact number field";
    else
	  $contact=test_input($_POST['contact']);
   if( empty($_POST['qualification']))
         echo  "Please fill in the empty qualification field";
	else
      $qual=test_input($_POST['qualification']);
   if( empty($_POST['profession']) )
         echo "Please fillin the empty profession field";  
    else  
      $prof=test_input($_POST['profession']); 
}


$username=$_SESSION['user'];
$up_query="update master set Name='$name', ContactNo='$contact', Qualification='$qual', Profession='$prof', Link_Photo='$link' where Username = '$username' ";

if(mysql_query($up_query))
$done=true;
else
$done=false;


  if( (isset($_POST['change']) && !empty($_POST['change'])) &&  $_SERVER['REQUEST_METHOD'] == 'POST')
  { 
    $picname= $_FILES['files']['name'];
    $pictype = $_FILES['files']['type'];
    $picsize = $_FILES['files']['size'];
    if(!empty($picname))
    {
      $link=GW_UPLOADPATH.$picname;
      if(($pictype== 'image/jpeg' || $pictype == 'image/png') && $picsize < GW_MAXFILESIZE)
      {
       if(move_uploaded_file($_FILES['files']['tmp_name'], $link))
       {
         $queri="update master set Link_Photo='$link' where Username='".$_SESSION['user']."'";
	     if(!mysql_query($queri))
					$picerr = 'Picture not updated.';
       }
      }
      else $picerr= "The picture should be of jpg or png format and 512kb or less!!";
    }
	else
$picerr="Please Chose a File";
  }




?>



<head>
<title>Profile</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype = "multipart/form-data">
 <table  cellpadding =10>
 <tr>
<td colspan = 3 align = "center"><h1><?php echo $_SESSION['user']."'s" ;?> Profile</h1></td>
</tr>
 <tr>
     <td>
	     Name:
	 </td>	 
     <td>
     	 <input type="text" name="name" value="<?php echo $name; ?>" >
	 </td>


      <td rowspan=5 valign = "top">
<img src="<?php echo $link ;?>" style="width:220px;height:220px" alt = "<?php echo $picerr; ?>"></img><br /><br><br>
<?php echo $picerr; ?>
<input type ="file" name = "files" id="Link_Photo" /><br /><br><br>
<input type = "submit" name = "change" value = "Change Photo" />


</td>
	 
 </tr>
 <tr>
     <td> 
         ContactNo: 
	 </td>
     <td>
	 <input type="text" name="contact" value="<?php echo $contact; ?>" ><br>
	 </td>
 </tr><br><tr></tr>
 <tr>
     <td> 
	 Qualification: 
	 </td>
	 <td>
	 <input type="text" name="qualification" value="<?php echo $qual; ?>" ><br>
	 </td>
 </tr>
 <tr>
     <td>
     Profession: 
	 </td>
	 <td>
	 <input type="text" name="profession" value="<?php echo $prof; ?>" > <br>
	 </td>
 </tr>
 

<tr><td>
<input type="submit" name="edit" value=" Edit "></td>
<td><a href = "profile.inc.php">Done</a></td>
</tr>
</form>
</body>
</html>