<?php
require_once 'core.inc.php';
if(loggedin()) {
	require_once 'header.php';
	require_once 'dbconnect.inc.php';
	unset($_SESSION['id']);
	$idd = "";
	$username = $_SESSION['user'];
	$rec_limit = 5;
	$offset = 0;
	
	if((isset($_POST['sold']) || isset($_POST['delete'])) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST["check_list"])) {
			$string = "";
			foreach($_POST["check_list"] as $x) {
				$string = $string.$x." OR ID = ";
			}
			$string = substr($string, 0, strlen($string) - 9);
			if(isset($_POST['sold'])) {
				$query = "update posts set sold = 1 where ID = $string";
			} else if(isset($_POST['delete'])) {
				foreach($_POST["check_list"] as $x) {
					unlink('posts/'.$x.'.jpg');
				}
				$query = "delete from posts where ID = $string";
			}
			if(!mysql_query($query)) {
				header('Location: '.$_SERVER['PHP_SELF'].'#failure');
			} else {
				header('Location: '.$_SERVER['PHP_SELF'].'#success');
			}
		}
	}
	
	if(isset($_POST['edit']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST["check_list"])) {
			foreach($_POST["check_list"] as $x) {
				$_SESSION["id"][$x] = $x;
				header('Location: editposts.php');
			}
		}
	}
	
	$query_run = getuserdata('*','posts','Username',$username);
	if($query_run != 0) {
		$rec_count = mysql_num_rows($query_run);
	} else {
		echo 'Unable to process your request';
	}
	
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
		$offset = ($page - 1) * $rec_limit;
	} else {
		$page = 1;
		$offset = 0;
	}
	$left_rec = $rec_count - ($page * $rec_limit);
} else {
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>History</title>
<script type="text/javascript" src="js/message.js"></script>
<script type="text/javascript" src="js/history-hash.js"></script>
<style>
.message {
	padding: 20px;
	color:white;
	background-color:black;
	margin: 10px auto;
	width: 50%;
	border-radius: 5px;
	box-shadow: 0px 0px 10px #783535;
	display:none;
	text-align:center;
}
.message.error {
	background-color: rgba(255,0,0,0.7);
}
.message.success {
	background-color: rgba(0,255,0,0.7);
}
</style>
</head>
<body>
<h2 align="center">HISTORY OF POSTS ADDED BY <?php echo $username; ?></h2>
<div id="alert-message" class="message"></div>
No. of books posted by <?php echo $username;?> =  <?php echo $rec_count;?>
<a style="float:right" href = "index.php"><i> Go to your profile</i> </center></a>
<?php
if($rec_count != 0) {
	echo "<form method = \"POST\" action = \"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?>\">
	<table align=\"center\" style='width:80%;background-color:rgba(255, 255, 255, 0.75);' cellpadding=\"10\">
	<tr>
	<th>ID</th>
	<th>Book</th>
	<th>Details</th>
	<th>Date Of Post</th>
	<th>Status(sold/unsold)</th>
	</tr>";
	$query = "select * from posts where Username='".$username."' order by sold, dateofpost desc, id desc"." LIMIT $offset,$rec_limit";
	if($query_run = mysql_query($query)) {
		while($query_row = mysql_fetch_array($query_run,MYSQL_ASSOC)) {
			$id = $query_row['ID'];
			$title = $query_row['Title'];
			$subject = $query_row['Subject'];
			$author = $query_row['Author'];
			$edition = $query_row['Edition'];
			$original_price = $query_row['Original_Price'];
			$selling_price = $query_row['Selling_Price'];
			$date = $query_row['dateofpost'];
			//  header("Content-type: image/jpeg");
			
			$sold = $query_row['sold'];
			echo "<tr>
			<td align = \"center\"><input type = \"checkbox\" name = \"check_list[]\" value = \"$id\">".$id."</td>";
			echo "<td align = \"center\">".$title."<br>
			<a href=\"posts/".$id.".jpg\"><img src = \"posts/".$id.".jpg\" style = \"width:125px;height:auto\"></a></td>
			<td>Subject: " .$subject. "<br>
			Author: ".$author."<br>
			Edition: ".$edition."<br>
			Original Price:".$original_price."<br>
			Selling Price: ".$selling_price."</td>
			<td align = \"center\">".$date."</td>
			<td align = 	\"center\">";
			if($sold == 1) {
				echo "Sold";
			} else {
				echo "Unsold";
			}
			echo "</td></tr>";
		}
	} else {
		echo 'Unable to process request';
	}
	
	$last = $page == 0 ? $page : $page - 1;
	$next = $left_rec == 0 ? $page : $page + 1;

	echo "<tr><td colspan=5 align=\"center\">";
	if( $page == 1 ) {
		if( $left_rec > 0 ) {
			echo "<a href=\"?page=".$next."\">Next</a> |";
		}
	} else if( $page > 1 ) {
		echo "<a href=\"?page=".$last."\">Previous</a> |";
		if( $left_rec > 0 )
		echo "<a href=\"?page=".$next."\">Next</a> |";
	}

	echo "";
}
?>
<input type = "submit" name = "sold" value = "Marked have been Sold">
<input type = "submit" name = "delete" value = "Delete Selected">
<input type = "submit" name = "edit" value = "Edit Selected">
</td></tr>
</table>
</form>
</body>
</html>