<?php

$cats = $_POST['cats'];
include("bd.php");

if(strlen($cats)>=1){
	$ids = explode(",",$cats);
$k = 1;

print("<table cellspacing=1 width=100% cellpadding=20 border=1 ><tr><td><center><b>№</b></center></td><td><center><b>Название инструкции</b></center></td><td><center><b>Категория</b></center></td><td><center><b>Удалить</b></center></td></tr>");

foreach($ids as $key => $value){
	$q = mysql_query("SELECT * FROM `inses` WHERE `cat_id`='$value' ");
	while($data = mysql_fetch_array($q)){
		$doc_name = $data['name'];
		$doc_cat = $data['cat_id'];
		$doc_id = $data['id'];
		$q2 = mysql_query("SELECT `name` FROM `cats` WHERE `id`='$doc_cat' ");
		$cat_info = mysql_fetch_array($q2);
		$cat_name = $cat_info['name'];
		
		print("<tr><td><center>".$k."</center></td><td><center><a target='_blank' href='edit.php?id=".$doc_id."'>".$doc_name."</center></td><td><center>".$cat_name."</center></td>");
		print("<td><center><a href='#' onClick=deldoc('".$doc_id."');>-Х-</a></center></td></tr>");
		$k++;
	}
}
print("</table>");
}
else{
$dq = mysql_query("SELECT * FROM `inses` ORDER BY `id` DESC LIMIT 10");
?>
<table cellspacing=1 width=100% cellpadding=20 border=1 ><tr><td><center><b>№</b></center></td><td><center><b>Название инструкции</b></center></td><td><center><b>Категория</b></center></td><td><center><b>Удалить</b></center></td></tr>
<?php
$k=1;
while($de = mysql_fetch_array($dq)){
	print("<tr>");
	print("<td>");
	print("<center>".$k."</center>");
	print("</td>");
	print("<td>");
	$doc_id = $de['id'];
	$doc_name = $de['name'];
	print("<center><a target='_blank' href='edit.php?id=".$doc_id."'>".$doc_name."</center>");
	print("</td>");
	print("<td>");
	$categoriwka = $de['cat_id'];
	$qwerty = mysql_query("SELECT `name` FROM `cats` WHERE `id`='$categoriwka' ");
	$qwerty_data = mysql_fetch_array($qwerty);
	print("<center>".$qwerty_data['name']."</center>");
	print("</td>");
	print("<td>");
	print("<center><a href='#' onClick=deldoc('".$doc_id."');>-Х-</a></center>");
	print("</td>");
	print("</tr>");
	$k++;
}
?>
</table>
<?php
}
if($k==1){
	print("<center><h3><font color='silver'>Не найдено ни одного документа</font></h3></center>");
}


?>