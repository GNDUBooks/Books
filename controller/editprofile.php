<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'dbconnect.inc.php';
	require_once '../header.php';
	$picErr = $nameErr = $contactErr = $qualErr = $profErr = '';
	$flag1 = $flag2 = $flag3 = $flag4 = true;
	$flag = false;
	$query_run = mysql_fetch_assoc(getuserdata('*','master','Username',$_SESSION['user']));
	
	$contact = $query_run['ContactNo'];

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
					echo $link = "../pro_photos/".$_SESSION['user'].'.jpg';
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
					$picErr = "The picture should be of jpg or png format";
				}
			} else {
				$link = $query_run['Link_Photo'];
			}
			if($flag) {
				$contact = ($contact == "") ? 'NULL' : $contact;
				$up_query = "update master set Name = '".$name."', ContactNo = ".$contact.", Qualification = '".$qual."', Profession = '".$prof."', Link_Photo = ".$link." where Username = '".$username."'";
				
				if(mysql_query($up_query)) {
					header('Location: ../index.php#editprofilesuccess');
				} else {
					header('Location: ../index.php#editprofilefailed');
				}
			} else {
				$_SESSION['editprofile']['picErr'] = $picErr;
				$_SESSION['editprofile']['nameErr'] = $nameErr;
				$_SESSION['editprofile']['contactErr'] = $contactErr;
				$_SESSION['editprofile']['qualErr'] = $qualErr;
				$_SESSION['editprofile']['profErr'] = $profErr;
				$_SESSION['editprofile']['pic'] = $pic;
				$_SESSION['editprofile']['name'] = $name;
				$_SESSION['editprofile']['contact'] = $contact;
				$_SESSION['editprofile']['qual'] = $qual;
				$_SESSION['editprofile']['prof'] = $prof;
				header('Location: '.$http_referer);
			}
		} else {
			$_SESSION['editprofile']['picErr'] = $picErr;
			$_SESSION['editprofile']['nameErr'] = $nameErr;
			$_SESSION['editprofile']['contactErr'] = $contactErr;
			$_SESSION['editprofile']['qualErr'] = $qualErr;
			$_SESSION['editprofile']['profErr'] = $profErr;
			$_SESSION['editprofile']['pic'] = $pic;
			$_SESSION['editprofile']['name'] = $name;
			$_SESSION['editprofile']['contact'] = $contact;
			$_SESSION['editprofile']['qual'] = $qual;
			$_SESSION['editprofile']['prof'] = $prof;
			header('Location: '.$http_referer);
		}
	}
} else {
	header('Location: ../index.php');
}
nocaching();
?>