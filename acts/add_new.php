<?php

include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}

include("bd.php");

$kol = $_POST['quest_num'];
$ins_name = $_POST['ins_name'];
$cat_id = $_POST['ins_catid'];
$q  = mysql_query("INSERT INTO `inses` (`name`,`cat_id`) VALUES ('$ins_name', '$cat_id') ");
$ins_id = mysql_insert_id();

for($i=0; $i<=$kol; $i++){
	if(isset($_POST['quest_'.$i])){
		$quest_text = $_POST['quest_'.$i];
		$q2 = mysql_query("INSERT INTO `quest` (`ins_id`, `text`, `local_id`) VALUES ('$ins_id', '$quest_text', '$i') ");
		$ans_num = $_POST['quest_'.$i.'_ans_num'];
		$quest_id = mysql_insert_id();
		for($j=0; $j<=$ans_num; $j++){
			if(isset($_POST['quest_'.$i.'_ans_'.$j])){
				$ans_text = $_POST['quest_'.$i.'_ans_'.$j];
				
				$q_link =  $_POST['quest_'.$i.'_ans_'.$j.'_link'];
				$q3 = mysql_query("INSERT INTO `answer` (`quest_id`, `text`, `q_link`, `ins_id`) VALUES ('$quest_id', '$ans_text', '$q_link', '$ins_id') ");
			}
		}
		$inf_num = $_POST['quest_'.$i.'_inf_num'];
		for($j=0; $j<=$inf_num; $j++){
			if(isset($_POST['quest_'.$i.'_inf_'.$j])){
				$inf_text_total = $_POST['quest_'.$i.'_inf_'.$j];
				$parts = explode("###",$inf_text_total);
				$inf_name = $parts[0];
				$inf_text = $parts[1];
				$q6 = mysql_query("INSERT INTO `info` (`quest_id`, `inf_name`, `text`) VALUES ('$quest_id','$inf_name','$inf_text')");
			}
		}
	}
}

header("location: ../edit.php?id=".$ins_id);


?>