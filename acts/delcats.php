<?php

include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}

include("bd.php");

function del_doc($insid){
	
	$sq = mysql_query("SELECT * FROM `quest` WHERE `ins_id`='$insid' ");
	while($qdata = mysql_fetch_array($sq)){
		$qid = $qdata['id'];
		$delinfq = mysql_query("DELETE FROM `info` WHERE `quest_id`='$qid' ");
	}
	$q1 = mysql_query("DELETE FROM `answer` WHERE `ins_id`='$insid' ");
	$q2 = mysql_query("DELETE FROM `quest` WHERE `ins_id`='$insid' ");
	$q3 = mysql_query("DELETE FROM `inses` WHERE `id`='$insid' ");
}

$cat_ids = $_POST['cats_id'];
$what = $_POST['whattodo'];

$ids = explode(",", $cat_ids);

foreach($ids as $id => $value){
	if($what==1 && $value!=1){
		$docs_in_cat_q = mysql_query("SELECT `id` FROM `inses` WHERE `cat_id`='$value' ");
		while($qweqwe1 = mysql_fetch_array($docs_in_cat_q)){
			$doid = $qweqwe1['id'];
			del_doc($doid);
		}
	}
	if($what==2){
		$upd = mysql_query("UPDATE `inses` SET `cat_id`='1' WHERE `cat_id`='$value' ");
	}
	if($what>2){
		$upd = mysql_query("UPDATE `inses` SET `cat_id`='".$what."' WHERE `cat_id`='$value' ");
	}
	if($value!=1){
		$q = mysql_query("DELETE FROM `cats` WHERE `id`='$value'");
	}
}

header("location: ../admin.php");

?>