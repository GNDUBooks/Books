<?php
ob_start();
session_start();
$current_file = $_SERVER['SCRIPT_NAME'];
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
	$http_referer = $_SERVER['HTTP_REFERER'];
}

/**
 * checks for user login
 * @return boolean true if logged in
 */
function loggedin() {
	if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
		return true;
	} else {
		return false;
	}
}

/**
 * executes query to fetch required data from database
 * @param String $field field or list of fields to be fetched separated by commas
 * @param String $table tablename from where data is to be fetched
 * @param String $column fieldname to identify row of table to be fetched
 * @param depends_$column value to be used to identify row 
 * @return String $query_run
 */
function getuserdata($field,$table,$column,$key){
	$query = "select ".$field." from ".$table." where ".$column." = '".$key."'";
	if($query_run = mysql_query($query)){
		return $query_run;
	}
}

/**
 * Sanitizes user inputs
 * @param string $data Something entered by user in text box
 * @return string $data sanitized data
 */
function test_input($data) {
	$data = trim($data);	
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

/**
 * validates username for required pattern and ensures its uniqueness in database
 * @param String $username Username entered by user
 * @return array assoiciative array of validated username, error if any and a flag
 */
function checkusername($username) {
	$value = array('username' => "",'usernameErr' => "",'flag' => true);
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
			$query1 = "select Username from confirmation where Username = '".$value['username']."'";
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

/**
 * validates name according to required pattern
 * @param String $name name entered by user
 * @return array associative array of name, error message if any and a flag
 */
function checkname($name){
	$value1 = array('name' => "",'nameErr' => "",'flag' => true);
	if (empty($name)) {
		$value1['nameErr'] = "Name is required";
		$value1['flag'] = false;
	} else {
		$value1['name']= test_input($name);
		// check if name only contains letters and whitespace
		if (!preg_match("/^[a-zA-Z ]{3,30}$/",$value1['name'])) {
			$value1['nameErr'] = "Only letters and white space allowed and length between 3 and 30";
			$value1['flag'] = false;
		}
	}
	return $value1;
}

/**
 * validates password for required pattern
 * @param String $pass password entered by user
 * @return array associative array of validated password, error message if any and a flag
 */
function checkpass($pass) {
	$value2 = array('pass' => "",'passErr' => "",'flag' => true);
	if (empty($pass)) {
		$value2['passErr'] = "Password is required";
		$value2['flag'] = false;
	} else {
		$value2['pass'] = test_input($pass);
		// check if password only contains letters and numbers
		if (!preg_match("/^[a-zA-Z0-9]{6,20}$/",$value2['pass'])) {
			$value2['passErr'] = "Only letters and numbers are allowed and length between 6 and 20";
			$value2['flag'] = false;
		}
	}
	return $value2;
}

/**
 * validates email for required pattern and ensures its uniqueness in database
 * @param String $email email entered by user
 * @return array associative array of validated email, error message if any and a flag
 */
function checkemail($email){
	$value3 = array('email' => "",'emailErr' => "",'flag' => true);
	if (empty($email)) {
		$value3['emailErr'] = "Email is required";
		$value3['flag'] = false;
	} else {
		$value3['email'] = test_input($email);
		// check if e-mail address is well-formed
		if(strlen($value3['email'])>50) {
			$value3['emailErr'] = "Email length exceeds 50 character limit";
			$value3['flag'] = false;
		} else {
			$query = "select Email from master where Email = '".$value3['email']."'";
			$query1 = "select Email from temp where Email = '".$value3['email']."'";
			if($query_run = mysql_query($query)) {
				if($query_run1 = mysql_query($query1)) {
					if(mysql_num_rows($query_run) != 0 || mysql_num_rows($query_run1) != 0){
						$value3['emailErr'] = 'Email Already Registered';
						$value3['flag'] = false;
					}
				}
			} else {
				$value3['emailErr']= 'Unable to validate your Email';
				$value3['flag'] = false;
			}
		}
	}
	return $value3;
}

/**
 * validates contact according to required pattern and ensures its uniqueness in database
 * @param String $contact contact no entered by user
 * @return array associative array of validated contact, error message if any and a flag
 */
function checkcontact($contact){
	$value4 = array('contact' => "",'contactErr' => "",'flag' => true);
	if(!empty($contact)) {
		$value4['contact'] = test_input($contact);
		// check if contact only contains numbers
		if (!preg_match("/^[0-9]{10,12}$/",$value4['contact'])) {
			$value4['contactErr'] = "Only numbers are allowed and length between 10-12";
			$value4['flag'] = false;
		} else {
			$query = "select ContactNo from master where ContactNo = '".$value4['contact']."'";
			$query1 = "select ContactNo from temp where ContactNo = '".$value4['contact']."'";
			if($query_run = mysql_query($query)) {
				if($query_run1 = mysql_query($query1)){
					if(mysql_num_rows($query_run) != 0 || mysql_num_rows($query_run1) != 0){
						$value4['contactErr'] = 'ContactNo entered already exists in database';
						$value4['flag'] = false;
					}
				}
			} else {
				$value4['contactErr'] = 'Unable to validate your ContactNo';
				$value4['flag'] = false;
			}
		}
	}
	return $value4;
}

/**
 * avoids caching of webpages
 */
function nocaching(){
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-control: post-check=0,pre-check=0",false);
	header("Pragma: no-cache");
}

ob_end_flush();
?>
