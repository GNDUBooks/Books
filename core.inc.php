<?php
ob_start();
session_start();
$current_file = $_SERVER['SCRIPT_NAME'];

if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
	$http_referer = $_SERVER['HTTP_REFERER'];
}

function loggedin() {
	if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
		return true;
	} else {
		return false;
	}
}

function getuserdata($field,$table,$column,$key){
	$query = "select ".$field." from ".$table." where ".$column." = '".$key."'";
	if($query_run = mysql_query($query)){
		return mysql_fetch_assoc($query_run);
	}
}


function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
function checkusername($username) {
	if (empty($username)) {
		$value['usernameErr'] = "Username is required";
		$value['flag'] = false;
	} else {
		$value['username'] = test_input($username);
		// check if username only contains letters and numbers
		if (!preg_match("/^[a-zA-Z0-9]{6,30}$/",$value['username'])) {
			$value['usernameErr'] = "Only letters and numbers allowed and length between 6 and 30";
			$value['flag'] = false;
		} else {
			$query = "select Username from master where Username = '".$value['username']."'";
			$query1 = "select Username from temp where Username = '".$value['username']."'";
			if($query_run = mysql_query($query)) {
				if($query_run1 = mysql_query($query1)) {
					if(mysql_num_rows($query_run) != 0 || mysql_num_rows($query_run1) != 0) {
						$value['usernameErr'] = 'Username already exists';
						$value['flag'] = false;
					}
				}
			} else {
				$value['usernameErr'] = 'Unable to validate your username';
				$value['flag'] = false;
			}
		}
	}

	return $value;
}

function checkname($name){
	if (empty($name)) {
		$value['nameErr'] = "Name is required";
		$value['flag'] = false;
	} else {
		$value['name']= test_input($name);
		// check if name only contains letters and whitespace
		if (!preg_match("/^[a-zA-Z ]{3,30}$/",$value['name'])) {
			$value['nameErr'] = "Only letters and white space allowed and length between 3 and 30";
			$value['flag'] = false;
		}
	}
	return $value;
}

function checkpass($pass) {
	if (empty($pass)) {
		$value['passErr'] = "Password is required";
		$value['flag'] = false;
	} else {
		$value['pass'] = test_input($pass);
		// check if password only contains letters and numbers
		if (!preg_match("/^[a-zA-Z0-9]{6,20}$/",$value['pass'])) {
			$value['passErr'] = "Only letters and numbers are allowed and length between 6 and 20";
			$value['flag'] = false;
		}
	}
	return $value;
}

function checkemail($email){
	if (empty($email)) {
		$value['emailErr'] = "Email is required";
		$value['flag'] = false;
	} else {
		$value['email'] = test_input($email);
		// check if e-mail address is well-formed
		if(strlen($value['email'])>50) {
			$value['emailErr'] = "Email length exceeds 50 character limit";
			$value['flag'] = false;
		} else {
			$query = "select Email from master where Email = '".$value['email']."'";
			$query1 = "select Email from temp where Email = '".$value['email']."'";
			if($query_run = mysql_query($query)) {
				if($query_run1 = mysql_query($query1)) {
					if(mysql_num_rows($query_run) != 0 || mysql_num_rows($query_run1) != 0){
						$value['emailErr'] = 'Email Already Registered';
						$value['flag'] = false;
					}
				}
			} else {
				$value['emailErr']= 'Unable to validate your Email';
				$value['flag'] = false;
			}
		}
	}
	return $value;
}

function checkcontact($contact){
	if(!empty($contact)) {
		$value['contact'] = test_input($contact);
		// check if contact only contains numbers
		if (!preg_match("/^[0-9]{10,12}$/",$value['contact'])) {
			$value['contactErr'] = "Only numbers are allowed and length between 10-12";
			$value['flag'] = false;
		} else {
			$query = "select ContactNo from master where ContactNo = '".$value['contact']."'";
			$query1 = "select ContactNo from temp where ContactNo = '".$value['contact']."'";
			if($query_run = mysql_query($query)) {
				if($query_run1 = mysql_query($query1)){
					if(mysql_num_rows($query_run) != 0 || mysql_num_rows($query_run1) != 0){
						$value['contactErr'] = 'ContactNo entered already exists in database';
						$value['flag'] = false;
					}
				}
			} else {
				$value['contactErr'] = 'Unable to validate your ContactNo';
				$value['flag'] = false;
			}
		}
	}
	return $value;
}
?>