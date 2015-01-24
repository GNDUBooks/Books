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
<script type="text/javascript">
    function updateTextInput(val) {
      document.getElementById('textInput').value=val; 
    }
	function updateSliderInput(val) {
		document.getElementById('rangeInput').value=val;
	}
</script>
</head>
<body>
<p align="left">
   <a href = "index.php" ><b>Back to Profile</b></center></a>
</p>

<center>  
<form input = 'search' method = 'get' action= "<?php htmlspecialchars($_SERVER["PHP_SELF"])?> ">

<table cellpadding =10>
<tr><h1>Search</h1></tr>

<tr>
<td><b>Title:</td><td><input type="text" name="title"></b></td>
<td><b>Subject:</td>
<td><select name = "subject">
<option value = "0" <?php if($subject == "0") { echo "selected = selected";}?>>Select Subject</option>
<option value = "Agriculture" <?php if($subject == "Agriculture") { echo "selected = selected";}?>>Agriculture</option>
<option value = "Architecture" <?php if($subject == "Architecture") { echo "selected = selected";}?>>Architecture</option>
<option value = "Arts" <?php if($subject == "Arts") { echo "selected = selected";}?>>Arts</option>
<option value = "Chemistry" <?php if($subject == "Chemistry") { echo "selected = selected";}?>>Chemistry</option>
<option value = "Commerce" <?php if($subject == "Commerce") { echo "selected = selected";}?>>Commerce</option>
<option value = "Computer Science" <?php if($subject == "Computer Science") { echo "selected = selected";}?>>Computer Science</option>
<option value = "Physics" <?php if($subject == "Physics") { echo "selected = selected";}?>>Engineering</option>
<option value = "Economics" <?php if($subject == "Economics") { echo "selected = selected";}?>>Economics</option>
<option value = "History" <?php if($subject == "History") { echo "selected = selected";}?>>History</option>
<option value = "Language" <?php if($subject == "Language") { echo "selected = selected";}?>>Language</option>
<option value = "Law" <?php if($subject == "Law") { echo "selected = selected";}?>>Law</option>
<option value = "Library Science" <?php if($subject == "Library Science") { echo "selected = selected";}?>>Library Science</option>
<option value = "Life Sciences" <?php if($subject == "Life Sciences") { echo "selected = selected";}?>>Life Sciences</option>
<option value = "Literature" <?php if($subject == "Literature") { echo "selected = selected";}?>>Literature</option>
<option value = "Management" <?php if($subject == "Management") { echo "selected = selected";}?>>Management</option>
<option value = "Mathematics" <?php if($subject == "Mathematics") { echo "selected = selected";}?>>Mathematics</option>
<option value = "Medicine and Health" <?php if($subject == "Medicine and Health") { echo "selected = selected";}?>>Medicine and Health</option>
<option value = "Philosophy and Pscychology" <?php if($subject == "Philosophy and Pscychology") { echo "selected = selected";}?>>Philosophy and Pscychology</option>
<option value = "Physics" <?php if($subject == "Physics") { echo "selected = selected";}?>>Physics</option>
<option value = "Political Science" <?php if($subject == "Political Science") { echo "selected = selected";}?>>Political Science</option>
<option value = "Religion" <?php if($subject == "Religion") { echo "selected = selected";}?>>Religion</option>
<option value = "Science" <?php if($subject == "Science") { echo "selected = selected";}?>>Science</option>
<option value = "Social Sciences and Sociology" <?php if($subject == "Social Sciences and Socialogy") { echo "selected = selected";}?>>Social Sciences and Socialogy</option>
</td>
<td><b>Author:</td><td><input type='text' name='author' ></b></td>
<td><input type="submit" name="search" value="Search"></td>
</tr>
</table>

</form>

<?php
if(!empty($_GET['search']) && (!empty($_GET['title']) || !empty($_GET['subject']) || !empty($_GET['author']) )  && !empty($_GET['page']) )
{  
  {
  echo "<div id='container'>
<div id='box'></div>
<div id='text'>";
  echo "<table cellpadding=10>";
  echo "<tr>
	  <td align=\"center\"><b>Book</b></td>
      <td align=\"center\"><b>Details</b></td>";
 
  echo gen_sortlinks($_GET['title'],$_GET['subject'],$_GET['author'],$sort);
  echo "<td align=\"center\">Offered Price</td></tr>";
  $num_pages=result($_GET['title'],$_GET['subject'],$_GET['author'],$sort,$results_per_page,$skip);
  }
  
}

?>

</table>
<?php
if ($num_pages > 1) {
echo generate_page_links($_GET['title'],$_GET['subject'],$_GET['author'], $sort, $_GET['page'], $num_pages);
}

?>
</div>
</div>

</center>

</body>
</html>