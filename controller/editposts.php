<?php
require_once 'core.inc.php';
if(loggedin()) {
	//require_once '../header.php';
	require_once 'dbconnect.inc.php';
	$username = $_SESSION['user'];
	$flag = true;
	$error[] = array();
	if(isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		$string = "";
		foreach($_SESSION["id"] as $x){
			$flag = $flag1 = $flag2 = $flag3 = $flag4 = $flag5 = $flag6 = $flag7 = $flag8 = $flag9 = true;
			if (empty($_POST["title"]["$x"]) || empty($_POST["subject"]["$x"]) || empty($_POST["author"]["$x"]) || empty($_POST["origprice"]["$x"]) || empty($_POST["sellprice"]["$x"])) {
				$error[$x] = "All fields are required";
				$flag = false;
				break;
			} else {
				$t = test_input($_POST["title"][$x]);
				$s = test_input($_POST["subject"][$x]);
				$a = test_input($_POST["author"][$x]);
				$e = test_input($_POST["edition"][$x]);
				$se = test_input($_POST["sellprice"][$x]);
				$o = test_input($_POST["origprice"][$x]);
				$error[$x] = "";
				if (!preg_match("/^[a-zA-Z0-9 ]{1,75}$/",$t)){
					$error[$x] = "Only characters,whitespaces and digits are required in Title and length upto 75<br>";
					$flag1 = false;
				} 
				if (!preg_match("/^[a-zA-Z ]{2,40}$/",$s)){
					$error[$x] .= "Only characters and whitespaces are required in Subject and length upto 40<br>";
					$flag2 = false;
				} 
				if (!preg_match("/^[a-zA-Z, ]{2,50}$/",$a)){
					$error[$x] .= "Only characters and whitespaces are required in Author and length upto 50<br>";
					$flag3 = false;
				}
				if (!preg_match("/^[1-9][0-9]{0,1}$/",$e)){
					$error[$x] .= "Only numbers are required between 1 to 50 in edition<br>";
					$flag4 = false;
				}
				if (($e > 50)){
					$error[$x] .= "edition of book should be less than 50<br>";
					$flag5 = false;
				}
				if (!preg_match("/^[1-9][0-9]{1,3}$/",$o)){
					$error[$x] .= "Only digits are required in Original Price and length upto 4<br>";
					$flag6 = false;
				}
				if (!preg_match("/^[1-9][0-9]{1,3}$/",$se)){
					$error[$x] .= "Only digits are required in Selling Price and length upto 4<br>";
					$flag7 = false;
				}
				if ((($o < 50) || ($o > 9999))){
					$error[$x] .= "original price should be in range of 50 to 9999<br>";
					$flag8 = false;
				}
				if (($se > (($o/2) + 10)) || ($se < (($o/3) - 10))) {
					$error[$x] .= "Selling price should in range one third to one half of original price<br>";
					$flag9 = false;
				}
				if($flag && $flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6 && $flag7 && $flag8 && $flag9){
					$string = "update posts set Title = '".$t."',Subject = '".$s."', Author = '".$a."', Edition = ".$e.", Original_Price = ".$o.", Selling_Price = ".$se." where ID = ".$x.";";
					if(!mysql_query($string)) {
						die(mysql_error());
					}
					unset($_SESSION["id"][$x]);
				} else {
					$_SESSION['editposts']['error'][$x] = $error[$x];
					header('Location: '.$http_referer);
				}
			}
		}
		if($flag && $flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6 && $flag7 && $flag8 && $flag9) {
			unset($_SESSION["id"]);
			header('Location: ../index.php#editpostsuccess');
		} else {
			$_SESSION['editposts']['error'][$x] = $error[$x];
			header('Location: '.$http_referer);
		}
	}
} else {
	header('Location: ../index.php');
}
?>