<?php
//require_once 'header.php';
require_once 'core.inc.php';
$curpass = $newpass = $conpass = "";
$curpassErr = $newpassErr = $conpassErr = "";
$flag = $flag1 = $flag2 = true;
if(loggedin()){
	if(isset($_POST['changepass']) && $_SERVER['REQUEST_METHOD'] == "POST"){
		if($_POST['curpass'] !== $_POST['newpass']) {
			$value = array('pass' => "",'passErr' => "",'flag' => "true");
			$value = checkpass($_POST['curpass']);
			$curpass = $value['pass'];
			$curpassErr = $value['passErr'];
			$flag = $value['flag'];
			
			$value1 = array('pass' => "",'passErr' => "",'flag' => "true");
			$value1 = checkpass($_POST['newpass']);
			$newpass = $value1['pass'];
			$newpassErr = $value1['passErr'];
			$flag1 = $value1['flag']; 
			
			if (empty($_POST["conpass"])) {
				$conpassErr = "Confirmation password is required";
				$flag2 = false;
			} else {
				$conpass = test_input($_POST["conpass"]);
				// check if password match
				if ($newpass != $conpass) {
					$conpassErr = "Passwords must match";
					$flag2 = false;
				}
			}
			
			if($flag && $flag1 && $flag2){
				require_once 'dbconnect.inc.php';
				$query_result = mysql_fetch_assoc(getuserdata('Password','login','Username',$_SESSION['user']));
				$redirect_page = "../index.php";
				if(md5($curpass) == $query_result['Password']){
					$query = "update login set Password = '".md5($newpass)."' where Username = '".$_SESSION['user']."'";
					if(mysql_query($query)){
						$query_result = mysql_fetch_assoc(getuserdata('Email','master','Username',$_SESSION['user']));
						$email = $query_result['Email'];
						$to      = $email;
						$subject = 'Change of your Account Password';
						$message = 'Your Password for your GNDUBooks account was changed from '.$curpass.' to '.$newpass;
						$headers = 'From: GNDUBooks <agndubooks@gmail.com>' . "\r\n" .
								   'X-Mailer: PHP/' . phpversion();

						mail($to, $subject, $message, $headers);
						header('Location: '.$redirect_page.'#passchanged');
					} else {
						header('Location: '.$redirect_page.'#passnotchanged');
					}
				} else {
					$_SESSION['changepass']['curpassErr'] = 'Incorrect current password';
					$_SESSION['changepass']['newpassErr'] = $newpassErr;
					$_SESSION['changepass']['conpassErr'] = $conpassErr;
					header('Location: ../changepass.php');
				}
			} else {
				$_SESSION['changepass']['curpassErr'] = $curpassErr;
				$_SESSION['changepass']['newpassErr'] = $newpassErr;
				$_SESSION['changepass']['conpassErr'] = $conpassErr;
				header('Location: '. $http_referer);
			}
		} else {
			$newpassErr = 'New Password should differ from old one.';
			$_SESSION['changepass']['curpassErr'] = $curpassErr;
			$_SESSION['changepass']['newpassErr'] = $newpassErr;
			$_SESSION['changepass']['conpassErr'] = $conpassErr;
			header('Location: '. $http_referer);
		}
	}
} else {
	header('Location: ../index.php');
}
?>