<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'dbconnect.inc.php';
	require_once 'header.php';
	$picerr = $error = '';
	$flag1 = $flag2 = $flag3 = $flag4 = true;
	$query_run = mysql_fetch_assoc(getuserdata('*','master','Username',$_SESSION['user']));
	$name = $query_run['Name'];
	$contact = $query_run['ContactNo'];
	$qual = $query_run['Qualification'];
	$prof = $query_run['Profession'];

	if($query_run['Link_Photo'] && is_file("pro_photos/".$_SESSION['user'].".jpg") && filesize("pro_photos/".$_SESSION['user'].".jpg") > 0) {
		$link = "pro_photos/".$_SESSION['user'].".jpg";
	} else {
		$link = "pro_photos/"."edit.jpg";
	}
	
	if( isset($_POST['edit']) && !empty($_POST['edit'] ) && empty($_POST['done'])) {
	
		$value1 = array('name' => "",'nameErr' => "",'flag' => true);
		$value1 = checkname($_POST['name']);
		$name = $value1['name'];
		$error = $value1['nameErr'];
		$flag1 = $value1['flag'];
		
		if($contact != $_POST['contact']){
			$value2 = array('contact' => "",'contactErr' => "",'flag' => true);
			$value2 = checkcontact($_POST['contact']);
			$contact = $value2['contact'];
			$error = $value2['contactErr'];
			$flag2 = $value2['flag'];
		}
		
		if(!empty($_POST['qualification'])) {
			$qual = test_input($_POST['qualification']);
			if (!preg_match("/^[a-zA-Z0-9 ]{3,50}$/",$qual)) {
				$error = 'Qualification must contain letters and length upto 50 chars';
				$flag3 = false;
			}
		}
		
		if(!empty($_POST['profession'])) {
			$prof = test_input($_POST['profession']); 
			if (!preg_match("/^[a-zA-Z0-9 ]{3,30}$/",$prof)) {
				$error = 'Profession must contain letters and length upto 30 chars';
				$flag4 = false;
			}
		}
		
		if($flag1 && $flag2 && $flag3 && $flag4) {
			$username = $_SESSION['user'];
			$picname = $_FILES['files']['name'];
			if(!empty($picname)) {
				$pictype = $_FILES['files']['type'];
				$picsize = $_FILES['files']['size'];
				if(($pictype == 'image/jpg' || $pictype == 'image/jpeg' || $pictype == 'image/png') && $picsize < 204800) {
					$link = "pro_photos/".$_SESSION['user'].'.jpg';
					if(move_uploaded_file($_FILES['files']['tmp_name'], $link)) {
						$link = 1;					
					} else {
						$link = 0;
					}
				} else {
					$picerr = "The picture should be of jpg or png format and 512kb or less!!";
					die($picerr);
				}
			} else {
				$link = $query_run['Link_Photo'];
			}
			
			$up_query = "update master set Name = '".$name."', ContactNo = '".$contact."', Qualification = '".$qual."', Profession = '".$prof."', Link_Photo = ".$link." where Username = '".$username."'";
			
			mysql_query($up_query);
			header('Location: index.php');
		}
	}
} else {
	header('Location: index.php');
}
nocaching();
echo $error;
?>



<head>
<title>Profile</title>
</head>
<body>
<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST" enctype = "multipart/form-data">
 <table  cellpadding =10>
 <tr>
<td colspan = 3 align = "center"><h1><?php echo $_SESSION['user']."'s" ;?> Profile</h1></td>
</tr>
 <tr>
     <td>
	     Name:
	 </td>	 
     <td>
     	 <input type="text" name = "name" value = "<?php echo $name; ?>" >
	 </td>


      <td rowspan = 5 valign = "top">
<img src = "<?php echo $link ;?>" style = "width:220px;height:220px" alt = "<?php echo $picerr; ?>"></img><br /><br><br>
<?php echo $picerr; ?>
<input type = "file" name = "files" id = "Link_Photo" /><br>
<a href = 'remove.php'>Remove Photo</a><br><br>

</td>
	 
 </tr>
 <tr>
     <td> 
         ContactNo: 
	 </td>
     <td>
	 <input type = "text" name = "contact" value = "<?php echo $contact; ?>" ><br>
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
 

<tr><td colspan = 3 align = "center">
<input type = "submit" name = "edit" value = "Edit"></td>
</tr>
</form>
</body>
</html>