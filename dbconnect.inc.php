<?php
$mysql_host = 'localhost';
$mysql_user = 'books';
$mysql_pass = 'access0288splendor';

$mysql_db = 'books';

if(!@mysql_connect($mysql_host,$mysql_user,$mysql_pass) || !@mysql_select_db($mysql_db)){
	die(mysql_error());
}
?>
