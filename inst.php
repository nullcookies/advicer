<html>

<head>
<title>Инструкция</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="main.css">
<script>
function openq(qid,nowqid){
	$("#q"+nowqid).css("display", "none");
	$("#q"+qid).css("display", "block");
}
</script>

</head>


<body>
<?php

$ins_id = $_GET['insid'];

include("acts/bd.php");

$ins_q = mysql_query("SELECT * FROM `inses` WHERE `id`='$ins_id' ");
$ins_data = mysql_fetch_array($ins_q);

print("<div id='wrap'>");
print("<center><h3>".$ins_data['name']."</h3></center><br>");

$quest_q = mysql_query("SELECT * FROM `quest` WHERE `ins_id`='$ins_id' ");

$k = 0;
while($quest_data = mysql_fetch_array($quest_q)){
		$quest_id = $quest_data['id'];
		print("<div id='q".$quest_data['local_id']."' ");
		if($k!=0){
			print("style='display:none;'>");
		}else print(">");
		$sur_text = $quest_data['text'];
		
		
		$inf_q = mysql_query("SELECT * FROM `info` WHERE `quest_id`='$quest_id' ");
		while($inf_data = mysql_fetch_array($inf_q)){
			$inf_name = $inf_data['inf_name'];
			$inf_text = $inf_data['text'];
			$sur_text = preg_replace('/\<'.$inf_name.'\>/', '<span class="tooltip">', $sur_text);
			$sur_text = preg_replace('/\<\/'.$inf_name.'\>/', '<span class="classic">'.$inf_text.'</span></span>', $sur_text);
		}
		
		print("<div class='surak'>".$sur_text."</div>");
		
		$ans_q = mysql_query("SELECT * FROM `answer` WHERE `quest_id`='$quest_id' ");
		
		while($ans_data = mysql_fetch_array($ans_q)){
			print("<div class='answer' onClick=openq(".$ans_data['q_link'].",".$quest_data['local_id'].");>".$ans_data['text']."</div>");
		}
		
		print("</div>");
		$k++;
}
print("</div>");

?>