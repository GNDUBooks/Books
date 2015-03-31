<?php
include ('header.php');
require_once('dbconnect.inc.php');
require_once ('core2.inc.php');
$sort=1;
if(!empty($_GET['sort']))
{
$sort=$_GET['sort'];
}
$num_pages=0;
$results_per_page=3;
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
$_GET['page']=$cur_page;
$skip = (($cur_page - 1) * $results_per_page);
$subject = "";
function generate_page_links($t,$s,$a, $sort, $cur_page, $num_pages) {
    $page_links = '';

	echo $t;
   
    if ($cur_page > 1) {
      $page_links .= '<a href="' . $_SERVER['PHP_SELF'] .'?title=' . $t .'&subject=' . $s .'&author=' . $a . '&sort=' . $sort . '&page=' . ($cur_page - 1) .'&search=Search"><b><-</b></a>';
    }
    else {
      $page_links .= '<- ';
    }

       for ($i = 1; $i <= $num_pages; $i++) {
      if ($cur_page == $i) {
        $page_links .= ' ' . $i;
      }
      else {
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?title=' . $t .'&subject=' . $s .'&author=' . $a  . '&sort=' . $sort . '&page=' . $i . '&search=Search"> <b>' . $i . '</b></a>';
      }
    }

   
    if ($cur_page < $num_pages) {
      $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?title=' . $t .'&subject=' . $s .'&author=' . $a . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '&search=Search"><b>-></b></a>';
    }
    else {
      $page_links .= ' ->';
    }

    return $page_links;
  }
function gen_sortlinks($t,$s,$a,$sort)
{
  $sort_links='';
  switch($sort)
  {
    case 1:
	{
	 $sort_links.='<td><a href="' . $_SERVER["PHP_SELF"] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=2&page='.$_GET['page'].'&search=Search">Selling Price</a></td>';
     $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=3&page='.$_GET['page'].'&search=Search">Post Date</a></td>';
	 
	 break;
	 }
	 case 3:
	 {
	   $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=1&page='.$_GET['page'].'&search=Search">Selling Price</a></td>';
     $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=4&page='.$_GET['page'].'&search=Search">Post Date</a></td>';
	 break;
	 }
	 default:
	 {
	  $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=1&page='.$_GET['page'].'&search=Search">Selling Price</a></td>';
      $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=3&page='.$_GET['page'].'&search=Search">Post Date</a></td>';
	  break; 
	 }
  }
  return $sort_links;
}

?>

<!DOCTYPE>
<html>
<head>
<title>
Search Books</title>
<script type="text/javascript" src="js/message.js"></script>
<script type="text/javascript" src="js/search-hash.js"></script>
<style>
.message {
	padding: 20px;
	color:white;
	background-color:black;
	margin: 10px auto;
	width: 40%;
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
<p align="left">
   <a href = "index.php" ><b>Back to Profile</b></center></a>
</p>
<div id="alert-message" class="message"></div>
<center>  
<form input = 'search' method = 'get' action= "<?php htmlspecialchars($_SERVER["PHP_SELF"])?> ">

<table cellpadding=10 style="background-color:rgba(255,255,255,0.75);">
<tr><td colspan=7 align="center"><h2>Search</h2></td></tr>

<tr>
<td><b>Title:</td><td><input type="text" name="title" value="<?php echo @$_GET['title'];?>"></b></td>
<td><b>Subject:</td>
<td><select name = "subject">
<option value = "0" <?php if($subject == "0") { echo "selected = selected";}?>>Select Subject</option>
<?php
$query = "select SubjectName from subject where SubjectName!='Other'";
if($queryrun = mysql_query($query)) {
	while($result = mysql_fetch_assoc($queryrun)){
		echo "<option value = \"".$result['SubjectName']."\"";
		if(@$_GET['subject'] == $result['SubjectName']){ echo "selected=selected"; } 
		echo ">".$result['SubjectName']."</option>";
	}
} else {
	die(mysql_error());
}
?>
<option value = "Other" <?php if($subject == "Other") { echo "selected = selected";}?> >Other</option>
</select></td>
<td><b>Author:</td><td><input type='text' name='author' value='<?php echo @$_GET['author']?>'></b></td>
<td><input type="submit" name="search" value="Search"></td>
</tr>
</table>

</form>

<?php
if(!empty($_GET['search']) && (!empty($_GET['title']) || !empty($_GET['subject']) || !empty($_GET['author']) )  && !empty($_GET['page']) )
{  
  {
  echo "<table cellpadding=10 style=\"background-color:rgba(255,255,255,0.75)\">";
  echo "<tr>
	  <td align=\"center\"><b>Book</b></td>
      <td align=\"center\"><b>Details</b></td>";
 
  echo gen_sortlinks($_GET['title'],$_GET['subject'],$_GET['author'],$sort);
  echo "<td align=\"center\">Offered Price</td></tr>";
  $num_pages=result(mysql_real_escape_string($_GET['title']),mysql_real_escape_string($_GET['subject']),mysql_real_escape_string($_GET['author']),$sort,$results_per_page,$skip);
  }
  
}

?>

</table>
<?php
if ($num_pages > 1) {
echo generate_page_links($_GET['title'],$_GET['subject'],$_GET['author'], $sort, $_GET['page'], $num_pages);
}

?>

</center>

</body>
</html>