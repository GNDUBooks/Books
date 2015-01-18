<?php
include ('header.php');
require_once('dbconnect.inc.php');
require ('core2.inc.php');
$sort=0;
if(!empty($_GET['sort']))
{
$sort=$_GET['sort'];
}
function gen_sortlinks($t,$s,$a,$sort)
{
  $sort_links='';
  switch($sort)
  {
    case 1:
	{
	 $sort_links.='<td><a href="' . $_SERVER["PHP_SELF"] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=2&search=Search">Selling Price</a></td>';
     $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=3&search=Search">Post Date</a></td>';
	 
	 break;
	 }
	 case 3:
	 {
	   $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=1&search=Search">Selling Price</a></td>';
     $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=4&search=Search">Post Date</a></td>';
	 break;
	 }
	 default:
	 {
	  $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=1&search=Search">Selling Price</a></td>';
      $sort_links.='<td><a href="' . $_SERVER['PHP_SELF'] . '?title=' . $_GET['title'] . '&subject=' . $_GET['subject'] . '&author=' . $_GET['author'] . '&sort=3&search=Search">Post Date</a></td>';
	  break; 
	 }
  }
  return $sort_links;
}

?>

<!DOCTYPE>
<html>
<title>
Search Books</title>
<body>
<p align="left">
   <a href = "profile.inc.php" ><b>Back to Profile</b></center></a>
</p>

<center>  
<form input = 'search' method = 'get' action= "<?php htmlspecialchars($_SERVER["PHP_SELF"])?> ">

<table cellpadding =10>
<tr><h1>Search</h1></tr>

<tr>
<td><b>Title:</td><td><input type="text" name="title"></b></td>
<td><b>Subject:</td><td><input type='text' name='subject' ></b></td>
<td><b>Author:</td><td><input type='text' name='author' ></b></td>
<td><input type="submit" name="search" value="Search"></td>
</tr>
</table>

</form>

<?php
if(!empty($_GET['search']) && (!empty($_GET['title']) || !empty($_GET['subject']) || !empty($_GET['author']) ) )
{
  
  
  {
  echo "<div id='container'>
<div id='box'></div>
<div id='text'>";
  echo "<table cellpadding =35>";
  echo '<tr><td><b>Image</b></td>';
  echo "<td ><b>Title</b></td>";
  echo "<td ><b>Subject</b></td>";
  echo "<td ><b>Author</b></td>";
  echo "<td ><b>Edition</b></td>";
  echo "<td ><b>By</b></td>";
  echo "<td ><b>Original Price</b></td>";
  echo gen_sortlinks($_GET['title'],$_GET['subject'],$_GET['author'],$sort);
  echo "</tr>";
  result($_GET['title'],$_GET['subject'],$_GET['author'],$sort);
  }
  
}
?>

</table>
</div>
</div>

</center>

</body>
</html>







