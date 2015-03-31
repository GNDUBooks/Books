<?php
ob_start();
session_start();
$current_file = $_SERVER['SCRIPT_NAME'];
define('GW_UPLOADPATH','pro_photos/');
define('GW_MAXFILESIZE',204800);
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$http_referer = $_SERVER['HTTP_REFERER'];
}

function build_query($t,$s,$a,$sort) {
	$search_query="select * from (select * from (select * from posts left join (select BookId as b,OfferedPrice from buyrequest where BuyerUser = '".$_SESSION['user']."') as t1 on t1.b = posts.ID) as t2 left join (select BookId,DateOfReport from report where Username = '".$_SESSION['user']."') as t3 on t3.BookId = t2.ID) as t4 where sold = '0' and NoReport < 3 and (";
	if(!empty($t)) {
		$search_words_t =explode(' ',str_replace(',',' ',$t));
	}
	if(!empty($s)) {
		$search_words_s =explode(' ',str_replace(',',' ',$s));
	}
	if(!empty($a)) {
		$search_words_a =explode(' ',str_replace(',',' ',$a));
	}
	$final_t=array();
    $final_s=array();
	$final_a=array();
	if(!empty($t)) {
		if(count($search_words_t)>0) {
			foreach($search_words_t as $words) {
				if(!empty($words)) {
					$final_t[]=$words;
				}
			}
		}
	}
	
	if(!empty($a)) {
		if(count($search_words_a)>0) {
			foreach($search_words_a as $words) {
				if(!empty($words)) {
					$final_a[]=$words;
				}
			}
		}
	}
    $where_list_t=array();
	$where_list_s=array();
	$where_list_a=array();
	if(count($final_t)>0) {
		foreach($final_t as $word) {
			$where_list_t[]="title" ." like '%$word%'";
		}
		$wt='('.implode(' or ',$where_list_t) . ')';
	  
	  //echo $wt;
	}
	if(!empty($s)) {
		$ws = "subject = '".$s."'";
    }
	if(count($final_a)>0) {
		foreach($final_a as $word) {
			$where_list_a[]=" author" ." like '%$word%'";
		}
		$wa = '('. implode(' or ',$where_list_a).')' ;
	  // echo $wa;
    }
 
	$where=array();
    if(!empty($wt)) {
		$where[] = $wt;
	}
	if(!empty($ws)) {
		$where[] = $ws;
	}
	if(!empty($wa)) {
		$where[] = $wa;
	}
	if(!empty($where)) {
		$where_clause = implode(' and ',$where);
	}
    if(!empty($where_clause)) {
		$search_query .= " $where_clause)";
	}
	switch($sort) {
		case 1: 
			$search_query .= " order by Selling_Price";
			break;
		case 2:
			$search_query .= " order by Selling_Price desc";
			break;
		case 3:
			$search_query .= " order by dateofpost";
		    break;	  
		case 4:	
			$search_query .= " order by dateofpost desc";
			break;
		default:{}
    }	  
	 
	return $search_query;
}
  
function result($t,$s,$a,$sort,$results_per_page,$skip) {
	$query=build_query($t,$s,$a,$sort);
	$set=mysql_query($query);
	$total=mysql_num_rows($set);
	$num_pages=ceil($total/$results_per_page);
	 
	$query.=" limit ".$skip.', ' . $results_per_page;
	$st=mysql_query($query);
	$tot=mysql_num_rows($st);
	 
	if($tot>0) {
		while ($result_set = mysql_fetch_array($st)) {
			$username=$result_set['Username'];
			
			$idd = $result_set['ID'];
			$title=$result_set['Title']; 
			$subject=$result_set['Subject'];
			$author=$result_set['Author'];
			$edition=$result_set['Edition'];
			$oriprice=$result_set['Original_Price'];
			$sellprice=$result_set['Selling_Price'];
			
			$link='posts/'.$result_set['ID'].'.jpg';
			$date=$result_set['dateofpost'];
			$requested = !empty($result_set['OfferedPrice']) ? true : false;
			$reported = !empty($result_set['DateOfReport']) ? true : false;
			echo "<tr><td>
			<table>
				<tr>
					<td align=\"center\"> " .$title. " </td><br>
				</tr>
				<tr>
					<td><img src = ".$link." style =  width:120px;height:120px  ></img></td>
				</tr>
			</table>
			</td>";	  
			echo " <td><br>
			<ul>
				<li>Subject:".$subject."</li>
				<li>Author:".$author."</li>
				<li>Edition:".$edition."</li>";
				if($_SESSION['user'] == $username) {
					echo "<li>From: You</li>";
				} else {
					echo "<li>From:".$username."</li>";
				}
				echo "<li>Original Price:Rs.".$oriprice."</li>
				 
			</ul>
			</td>";
			$min = round(($oriprice / 3),-1);
			$max = round(($oriprice / 2),-1);
			echo "<td align=\"center\"><b>Rs. ".$sellprice."</b></td>
			<td>".substr($date,0,10)."</td>
			<td>
			<table>
				<tr>
					<td align=\"left\">".$min."</td>
					<td align=\"right\">".$max."</td>
				</tr>
				<tr>
					<td colspan=\"2\"><input type = \"range\" id=\"rangeInput".$idd."\" name=\"offerrange\" min = ".$min." max=".$max." step = \"10\" onchange=\"updateTextInput(this.value,$idd);\"></td>
				</tr>";
				if($_SESSION['user'] != $username) {
				//	echo "<form method=\"POST\" action=\"sendoffer.php\">
					echo "<form action=\"sendoffer.php\" method=\"POST\" onsubmit=\"return confirm('Do you want to send this offer?');\">
					<tr>
						<td align=\"center\" colspan=\"2\"><input type=\"number\" id=\"textInput".$idd."\" name=\"offer[$idd]\" min=".$min." max=".$max." value=\"".round((($min + $max) / 2),-1) ."\" step=\"10\" onchange=\"updateSliderInput(this.value,$idd)\" >
						</td>
					</tr>
					<tr>
					<td align=\"center\" colspan=\"2\"><button type=\"submit\" id=\"$idd\" name=\"sendoffer[$idd]\"";
					if(!$requested) { 
						echo ">Send Offer";
					} else {
						echo " disabled=\"disabled\">Already Requested";
					}
					echo "</button></td>
					</tr>
					</form>";
				}
			echo "</table>
			<td>";
			if($_SESSION['user'] != $username) {
				echo "<form method = 'POST' action = 'report.php' onsubmit=\"return confirm('If reported advertisement is genuine then your account may be suspended. Are you sure you want to report advertisement?');\">
				<button type = ".'submit'." name = \"reportadd[$idd]\" "; 
				if(!$reported) {
					echo ">Report";
				} else {
					echo "disabled=\"disabled\">Already Reported";
				}
				echo "</button></form>";
			}
			echo "</td></tr>";
			
		}
	} else {
		echo "<b>Sorry... No results Found!</b>";
	}
	return $num_pages;
	 
	for($i=1; $i<=$total;$i+=1) {
		if(!empty($_GET["'add".$i."'"])) {
			header('Location: index.php');
		}
	}
}
?> 