<?php
require_once 'header.php';
session_start();
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
	$http_referer = $_SERVER['HTTP_REFERER'];
	require_once 'dbconnect.inc.php';
	foreach($_POST["sendoffer"] as $x => $x_value) {
		$date = date("Y-m-d");
		$query = "select count(BookId) as no from buyrequest where BuyerUser = '".$_SESSION['user']."' and DateOfOffer like '".$date."_________'";
		if($query_run = mysql_query($query)) {
			if(mysql_result($query_run,0,'no') < 5){
				$query1 = "insert into buyrequest (BookId,BuyerUser,OfferedPrice) values ('".$x."','".$_SESSION['user']."','".$_POST["offer"][$x]."')";
				if($query_run1 = mysql_query($query1)){
					$query2 = "select master.Email,posts.Selling_Price,posts.Title from master,posts where posts.Username = master.Username and ID = '".$x."'";
					$query3 = "select Email from master where Username = '".$_SESSION['user']."'";
					if($query_run3 = mysql_query($query3)){
						$email = mysql_result($query_run3,0,'Email');
						if($query_run2 = mysql_query($query2)){
							$to = mysql_result($query_run2,0,'Email');
							$price = mysql_result($query_run2,0,'Selling_Price');
							$title = mysql_result($query_run2,0,'Title');
							$subject = "Offer for ".$title." book";
							$message = $_SESSION['user']." wants to buy your book named ".$title." posted against id ".$x.
							" for Rs. ".$_POST["offer"][$x]." which you wanted to sell at Rs. ".$price.
							". You can further contact buyer through mail. Email of buyer is ".$email.".";
							$headers = 'From: GNDUBooks <agndubooks@gmail.com>' . '\r\n' .
							   'Reply-To:  agndubooks@gmail.com'. '\r\n' .
							   'X-Mailer: PHP/' . phpversion();
							if(mail($to, $subject, $message, $headers)){
								header('Location: '.$http_referer.'#sent');
							} else {
								mysql_query("delete from buyrequest where BookId = '".$x."'");
								header('Location: '.$http_referer.'#notsent');
							}
						}
					} else {
						header('Location: '.$http_referer.'#dbproblem');
					}
				} else {
					header('Location: '.$http_referer.'#alreadysent');
				}
			} else {
				header('Location: '.$http_referer.'#offerlimitreached');
			}
		} else {
			header('Location: '.$http_referer.'#dbproblem');
		}
	}
} else {
	header("Location: index.php");
}
?>