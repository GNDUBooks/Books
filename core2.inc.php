<?php
ob_start();
session_start();
$current_file = $_SERVER['SCRIPT_NAME'];
define('GW_UPLOADPATH','pro_photos/');
define('GW_MAXFILESIZE',204800);
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
	$http_referer = $_SERVER['HTTP_REFERER'];
}


function build_query($t,$s,$a,$sort)
{  
   $search_query="select * from posts";
   if(!empty($t))
   $search_words_t =explode(' ',str_replace(',',' ',$t));
   if(!empty($s))
   $search_words_s =explode(' ',str_replace(',',' ',$s));
   if(!empty($a))
   $search_words_a =explode(' ',str_replace(',',' ',$a));
   
   $final_t=array();
    $final_s=array();
	 $final_a=array();
	 if(!empty($t)){
   if(count($search_words_t)>0)
   {
     foreach($search_words_t as $words)
	 {
	  if(!empty($words))
	  {
	    $final_t[]=$words;
	  }
	 }
	 
    }}
	if(!empty($s)){
	if(count($search_words_s)>0)
   {
     foreach($search_words_s as $words)
	 {
	  if(!empty($words))
	  {
	    $final_s[]=$words;
	  }
	 }
	 }
    }
	if(!empty($a)){
   if(count($search_words_a)>0)
   {
     foreach($search_words_a as $words)
	 {
	  if(!empty($words))
	  {
	    $final_a[]=$words;
	  }
	 }
   }
   }
    $where_list_t=array();
	$where_list_s=array();
	$where_list_a=array();
	    if(count($final_t)>0)
    {
      foreach($final_t as $word)
      {
	    
        $where_list_t[]="title" ." like '%$word%'";
      }
	  
	  $wt=implode(' or ',$where_list_t) ;
	  
	  //echo $wt;
	  }
	if(count($final_s)>0)
    {
      foreach($final_s as $word)
      {
	    
        $where_list_s[]="subject" ." like '%$word%'";
      }
	  $ws=implode(' or ',$where_list_s) ;
	  	  // echo $ws;
    }
	if(count($final_a)>0)
    {
      foreach($final_a as $word)
      {
	    
        $where_list_a[]="author" ." like '%$word%'";
      }
	  $wa=implode(' or ',$where_list_a) ;
	  // echo $wa;
    }
 
 $where=array();
 $where[]="sold = '0'";
    if(!empty($wt))
    $where[]=$wt ;
	if(!empty($ws))
    $where[]=$ws;
	if(!empty($wa))
    $where[]=$wa;
	if(!empty($where))
	$where_clause=implode(' and ',$where);
	
    if(!empty($where_clause))
    $search_query.=" where $where_clause";
	
	switch($sort)
	{
	  case 1:
	  { $search_query .= " order by Selling_Price";
	 	   break;}
	  case 2:{
	    $search_query .= " order by Selling_Price desc";
	    break;}
	  case 3:{
        $search_query .= " order by dateofpost";
		
	    break;}	  
      case 4:	{	
	    $search_query .= " order by dateofpost desc";
	    break;}
	  default:{}
    }	  
	 
	return $search_query;
}

function result($t,$s,$a,$sort)
{
 $query=build_query($t,$s,$a,$sort);
 $set=mysql_query($query);
 if(mysql_num_rows($set)>0){
  while ($result_set= mysql_fetch_array($set)){
   $title=$result_set['Title']; 
   $subject=$result_set['Subject'];
   $author=$result_set['Author'];
   $edition=$result_set['Edition'];
   $oriprice=$result_set['Original_Price'];
   $sellprice=$result_set['Selling_Price'];
   $username=$result_set['Username'];
   $link='posts/'.$result_set['ID'].'.jpg';
   $date=$result_set['dateofpost'];

   echo '<tr>';
   echo '<td><img src = '.$link.' style =  width:120px;height:120px  ></img></td>';
   echo '<td>'.$title.'</td>';
   echo '<td>'. $subject.'</td>';
   echo '<td>'.$author.'</td>';
   echo '<td>'. $edition.'</td>';
   echo '<td>'.$username.'</td>';
   echo '<td>'.$oriprice.'</td>';
   echo '<td>'.$sellprice.'</td>';
   echo '<td>'.substr($date,0,10).'</td>';
   echo '</tr>';
   }
 }
 else{
   echo "<b>Sorry... No results Found!</b>";
	 }
}	 
	 

?> 