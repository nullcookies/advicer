<?php

include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}

	include("bd.php");
	$insid = $_GET['id'];
	
	$sq = mysql_query("SELECT * FROM `quest` WHERE `ins_id`='$insid' ");
	while($qdata = mysql_fetch_array($sq)){
		$qid = $qdata['id'];
		$delinfq = mysql_query("DELETE FROM `info` WHERE `quest_id`='$qid' ");
	}
	$q1 = mysql_query("DELETE FROM `answer` WHERE `ins_id`='$insid' ");
	$q2 = mysql_query("DELETE FROM `quest` WHERE `ins_id`='$insid' ");
	$q3 = mysql_query("DELETE FROM `inses` WHERE `id`='$insid' ");
?>