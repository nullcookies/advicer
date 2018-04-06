<?php

include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}


$kol = $_POST['quest_num'];
$ins_name = $_POST['ins_name'];
$ins_id = $_POST['ins_id'];
$ins_catid = $_POST['ins_cat'];

include("bd.php");

$ins_q = mysql_query("UPDATE `inses` SET `name`='$ins_name' WHERE `id`='$ins_id' ");
$ins_q2 = mysql_query("UPDATE `inses` SET `cat_id`='$ins_catid' WHERE `id`='$ins_id' ");

for($i=0; $i<=$kol; $i++){
			//Если вопрос вообще существует
			if(isset($_POST['quest_'.$i])){
				//Если вопрос уже был в базе (обновляем), значит должен быть quest_bdid_
				if(isset($_POST['quest_bdid_'.$i])){
					$q_bdid = $_POST['quest_bdid_'.$i];
					$q_text = $_POST['quest_'.$i];
					$q_upd = mysql_query("UPDATE `quest` SET `text`='$q_text' WHERE `id`='$q_bdid'");
					//Обновляем ответы вопроса
					$ans_num = $_POST['quest_'.$i.'_ans_num'];
					for($j=0; $j<=$ans_num; $j++){
						//Если ответ  передан клиентом, который есть в базе
						if(isset($_POST['quest_'.$q_bdid.'_ans_'.$j])){
							//Если ответ есть в базе, обновляем
							if(isset($_POST["ans_bdid_".$q_bdid."_".$j]) && $_POST["ans_del_bdid_".$q_bdid."_".$j]!="DEL"){
								$ans_text = $_POST['quest_'.$q_bdid.'_ans_'.$j];
								$q_link =  $_POST['quest_'.$q_bdid.'_ans_'.$j.'_link'];
								$ansid = $_POST["ans_bdid_".$q_bdid."_".$j];
								$q_ans_upd = mysql_query("UPDATE `answer` SET `text`='$ans_text', `q_link`='$q_link' WHERE `quest_id`='$q_bdid' AND `id`='$ansid'  ");
							}
						}else{
							//Если ответ не передан и был в базе, удаляем
							if(isset($_POST["ans_del_bdid_".$q_bdid."_".$j]) && $_POST["ans_del_bdid_".$q_bdid."_".$j]=="DEL"){
								$ansid = $_POST["ans_bdid_".$q_bdid."_".$j];
								$ans_del_q = mysql_query("DELETE FROM `answer` WHERE `id`='$ansid'");
							}
						}
						//Если ответ передан, но не был в базе
						if(isset($_POST['quest_'.$i.'_ans_'.$j]) && !isset($_POST["ans_bdid_".$q_bdid."_".$j])){
								//Если ответ новый, добавляем
								$ans_text = $_POST['quest_'.$i.'_ans_'.$j];
								$q_link =  $_POST['quest_'.$i.'_ans_'.$j.'_link'];
								$ans_add_bd = mysql_query("INSERT INTO `answer` (`quest_id`, `text`, `q_link`, `ins_id`) VALUES ('$q_bdid', '$ans_text', '$q_link', '$ins_id') ");
			
						}
					}
					//Обновляем подсказки
					$inf_num = $_POST['quest_'.$i.'_inf_num'];
					for($j=0; $j<=$inf_num; $j++){
						//Если подсказка была передана клиентом
						if(isset($_POST['quest_'.$q_bdid.'_inf_'.$j])){
						
						//Если подсказка есть в базе, обновляем
							if(isset($_POST["inf_bdid_".$q_bdid."_".$j]) && $_POST["inf_del_bdid_".$q_bdid."_".$j]!="DEL"){
								$inf_text_total = $_POST['quest_'.$q_bdid.'_inf_'.$j];
								$parts = explode("###",$inf_text_total);
								$inf_name = $parts[0];
								$inf_text = $parts[1];
								
								$infid = $_POST["inf_bdid_".$q_bdid."_".$j];
								$q_ans_upd = mysql_query("UPDATE `info` SET `text`='$inf_text', `inf_name`='$inf_name' WHERE `quest_id`='$q_bdid' AND `id`='$infid'  ");
							}
						}else{
							//Если подсказка не передана и была в базе, удаляем
							if(isset($_POST["inf_del_bdid_".$q_bdid."_".$j]) && $_POST["inf_del_bdid_".$q_bdid."_".$j]=="DEL"){
								$infid = $_POST["inf_bdid_".$q_bdid."_".$j];
								$ans_del_q = mysql_query("DELETE FROM `info` WHERE `id`='$infid'");
							}
						}	
						
						//Если подсказка передана, но не была в базе
						if(isset($_POST['quest_'.$i.'_inf_'.$j]) && !isset($_POST["inf_bdid_".$q_bdid."_".$j])){
								//Если подсказка новая, добавляем
								$inf_text_total = $_POST['quest_'.$i.'_inf_'.$j];
								$parts = explode("###",$inf_text_total);
								$inf_name = $parts[0];
								$inf_text = $parts[1];
								$inf_add_bd = mysql_query("INSERT INTO `info` (`quest_id`, `inf_name`, `text`) VALUES ('$q_bdid', '$inf_name', '$inf_text') ");
			
						}
						
					}
				}else{
					//Если вопрос новый (добавляем)
					$q_text = $_POST['quest_'.$i];
					$q_insert = mysql_query("INSERT INTO `quest` (`ins_id`, `text`, `local_id`) VALUES ('$ins_id', '$q_text', '$i') ");
					$quest_id = mysql_insert_id();
					//Добавляем ответы вопроса
					$ans_num = $_POST['quest_'.$i.'_ans_num'];
					for($y=0; $y<=$ans_num; $y++){
						if(isset($_POST['quest_'.$i.'_ans_'.$y])){
							$ans_text = $_POST['quest_'.$i.'_ans_'.$y];
				
							$q_link =  $_POST['quest_'.$i.'_ans_'.$y.'_link'];
							$q3 = mysql_query("INSERT INTO `answer` (`quest_id`, `text`, `q_link`, `ins_id`) VALUES ('$quest_id', '$ans_text', '$q_link', '$ins_id') ");
						}
					}
					//Добавляем подсказки вопроса
					$inf_num = $_POST['quest_'.$i.'_inf_num'];
					for($y=0; $y<=$inf_num; $y++){
						if(isset($_POST['quest_'.$i.'_inf_'.$y])){
							$inf_text_total = $_POST['quest_'.$i.'_inf_'.$y];
							$parts = explode("###",$inf_text_total);
							$inf_name = $parts[0];
							$inf_text = $parts[1];
							$q4 = mysql_query("INSERT INTO `info` (`quest_id`, `inf_name`, `text`) VALUES ('$quest_id', '$inf_name', '$inf_text') ");
						}
					}
				}
			}else{
				//Если вопрос не существует (удален клиентом, но был в базе)
				if(isset($_POST['quest_del_bdid_'.$i]) && $_POST['quest_del_bdid_'.$i]=="DEL" ){
					$q_bdid = $_POST['quest_bdid_'.$i];
					$q_del = mysql_query("DELETE FROM `quest` WHERE `id`='$q_bdid' ");
					$ans_del = mysql_query("DELETE FROM `answer` WHERE `quest_id`='$q_bdid' ");
					$inf_del = mysql_query("DELETE FROM `info` WHERE `quest_id`='$q_bdid' ");
				}
			}
}

header("location: ../edit.php?id=".$ins_id);

?>