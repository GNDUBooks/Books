<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'dbconnect.inc.php';
	require_once 'header.php';
	$picerr = $nameErr = $contactErr = $qualErr = $profErr = '';
	$flag1 = $flag2 = $flag3 = $flag4 = true;
	$flag = false;
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
			$contactErr = $value2['contactErr'];
			$flag2 = $value2['flag'];
		}
		
		if(!empty($_POST['qualification'])) {
			$qual = test_input($_POST['qualification']);
			if (!preg_match("/^[a-zA-Z0-9 ]{2,50}$/",$qual)) {
				$qualErr = 'Qualification must contain letters and length upto 50 chars';
				$flag3 = false;
			}
		}
		
		if(!empty($_POST['profession'])) {
			$prof = test_input($_POST['profession']); 
			if (!preg_match("/^[a-zA-Z0-9 ]{2,30}$/",$prof)) {
				$profErr = 'Profession must contain letters and length upto 30 chars';
				$flag4 = false;
			}
		}
		
		if($flag1 && $flag2 && $flag3 && $flag4) {
			$username = $_SESSION['user'];
			$picname = $_FILES['files']['name'];
			$flag = true;
			if(!empty($picname)) {
				$pictype = $_FILES['files']['type'];
				
				if(($pictype == 'image/jpg' || $pictype == 'image/jpeg' || $pictype == 'image/png')) {
					echo $link = "pro_photos/".$_SESSION['user'].'.jpg';
					require_once 'upload.inc.php';
					echo $source_img = $_FILES['files']['tmp_name'];
					if(compress($source_img, $link, 50)) {
						$link = 1;					
					} else {
						$link = 0;
						$flag = false;
					}
				} else {
					$flag = false;
					$picerr = "The picture should be of jpg or png format";
				}
			} else {
				$link = $query_run['Link_Photo'];
			}
			if($flag) {
				$contact = ($contact == "") ? 'NULL' : $contact;
				$up_query = "update master set Name = '".$name."', ContactNo = ".$contact.", Qualification = '".$qual."', Profession = '".$prof."', Link_Photo = ".$link." where Username = '".$username."'";
				
				if(mysql_query($up_query)) {
					header('Location: index.php#editprofilesuccess');
				} else {
					header('Location: index.php#editprofilefailed');
				}
			}
		}
	}
} else {
	header('Location: index.php');
}
nocaching();
?>



<head>
<title>Profile</title>
</head>
<body>
<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST" enctype = "multipart/form-data">
 <table align="center" cellpadding=10 style="background-color:rgba(255,255,255,0.75); width:50%">
 <tr>
<td colspan = 3 align = "center"><h2><?php echo $_SESSION['user']."'s" ;?> Profile</h2></td>
</tr>
 <tr>
    <td>
	    Name:
	</td>	 
    <td style="color:red;">
    	<input type="text" name = "name" size="30" value = "<?php echo $name; ?>" ><br>
		<?php echo $nameErr; ?><br>
	 </td>


      <td rowspan = 4 valign = "top">
<img src = "<?php echo $link ;?>" style = "width:220px;height:auto" alt = "<?php echo $picerr; ?>"></img><br /><br><br>
<?php echo $picerr; ?>
<input type = "file" name = "files" id = "Link_Photo" /><br>
<a href = 'remove.php'>Remove Photo</a><br><br>

</td>
	 
 </tr>
 <tr>
     <td> 
         ContactNo: 
	 </td>
     <td style="color:red;">
	 <input type = "text" name = "contact" size="30" value = "<?php echo $contact; ?>" ><br>
	 <?php echo $contactErr; ?><br>
	 </td>
 </tr>
 <tr>
     <td> 
	 Qualification: 
	 </td>
	 <td style="color:red;">
	 <input type="text" name="qualification" size="30" value="<?php echo $qual; ?>" ><br>
	 <?php echo $qualErr; ?><br>
	 </td>
 </tr>
 <tr>
     <td>
     Profession: 
	 </td>
	 <td align="left">
	 <input type="text" name="profession" size="30" value="<?php echo $prof; ?>" > <br>
	 <?php echo $profErr; ?><br>
	 </td>
 </tr>
 

<tr><td colspan = 3 align = "center">
<input type = "submit" name = "edit" value = "Update"></td>
</tr>
</form>
</body>
</html>