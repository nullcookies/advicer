<html>

<head>
<title>Редактор инструкций</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="func.js"></script>
<script>
$(document).ready(function() {
    $(this).keydown(function(e) {
        if(e.keyCode==107){
			e.preventDefault();
			add_quest();
		}
    });
});
</script>
<?php
include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}
?>
<?php
	include("acts/bd.php");
	$idi = $_GET['id'];
	$ins_q = mysql_query("SELECT * FROM `inses` WHERE `id`='$idi'");
	//Достаем имя инструкции
	$ins_data = mysql_fetch_array($ins_q);
	$ins_name = $ins_data['name'];
	//Достаем айди категории
	$ins_cat_id = $ins_data['cat_id'];
	//Запрос для имени категории
	$catq = mysql_query("SELECT * FROM `cats` WHERE `id`='$ins_cat_id' ");
	$cat_data = mysql_fetch_array($catq);
	$cat_name = $cat_data['name'];
	
	//Достаем все категории
	$allcatsq = mysql_query("SELECT * FROM `cats`");
	
	//Достаем все вопросы инструкции
	$quests_q = mysql_query("SELECT * FROM `quest` WHERE `ins_id`='$idi' ");
	//$q_num = mysql_num_rows($quests_q);
	
	//Достаем максимальное значение локал_айди для текущей инструкции
	$max_num_q = mysql_query("SELECT `local_id` FROM `quest` WHERE `ins_id`='$idi' ORDER BY `local_id` DESC LIMIT 1");
	$max_num_data = mysql_fetch_array($max_num_q);
	$q_num = $max_num_data['local_id'];
?>
</head>

<body>

<form method="POST" action="acts/save.php">
<input type="submit" value="Сохранить"><br>
<input type="hidden" name="quest_num" id="quest_num" value="<?php echo $q_num;?>">

<input type="hidden" name="ins_id" value="<?php echo $idi; ?>">

Наименование инструкции: <input type="text" name="ins_name" style="width:400px;" value="<?php echo $ins_name; ?>">
Категория инструкции
<select name="ins_cat">
<option value="<?php echo $ins_cat_id;?>"><?php echo $cat_name; ?></option>
<?php
	while($cats_data = mysql_fetch_array($allcatsq)){
		$thiscatid = $cats_data['id'];
		$thiscatname = $cats_data['name'];
		if($thiscatid!=$ins_cat_id){
			print("<option value=".$thiscatid.">");
			print($thiscatname);
			print("</option>");
		}
	}
?>
</select>
<div id="questions">
	<?php
		$q = '"';
		while($q_data = mysql_fetch_array($quests_q)){
			$q_n = $q_data['local_id'];
			$q_id = $q_data['id'];
			$q_text = $q_data['text'];
			$ans_q = mysql_query("SELECT * FROM `answer` WHERE `quest_id`='$q_id'");
			$ans_total_num = mysql_num_rows($ans_q);
			//$ans_total_num++;
			$inf_q = mysql_query("SELECT * FROM `info` WHERE `quest_id`='$q_id'");
			$inf_total_num = mysql_num_rows($inf_q);
			$inf_total_num++;
			print("<input type='hidden' id='quest_del_bdid_".$q_n."' name='quest_del_bdid_".$q_n."' value=''>");
			print("<input type='hidden' name='quest_bdid_".$q_n."' value='".$q_id."'>");
			print("<div class='q".$q_n."'><table><tr><td>Вопрос - ".$q_n."<br><textarea cols=40 rows=5 name='quest_".$q_n."' id='quest_".$q_n."'>".$q_text."</textarea><br>");
			print("<a onClick='add_ans_to(".$q.$q_n.$q.")'>Добавить ответ</a> | ");
			print("<input type='hidden' name='quest_".$q_n."_ans_num' id='quest_".$q_n."_ans_num' value='".$ans_total_num."'>");
			print("<input type='hidden' name='quest_".$q_n."_inf_num' id='quest_".$q_n."_inf_num' value='".$inf_total_num."'>");
			print("<a onClick='add_inf_to(".$q.$q_n.$q.")'>Добавить i</a> | ");
			print("<a onClick='del_quest(".$q.$q_n.$q.")'><font color='red'>Удалить</font></a>");
			print("<div id='anses_".$q_n."'>");
				$k = 1;
				while($ans_data = mysql_fetch_array($ans_q)){
					$q_link = $ans_data['q_link'];
					$ans_bdid = $ans_data['id'];
					$ans_text = $ans_data['text'];
					print("<input type='hidden' id='ans_del_bdid_".$q_id."_".$k."' name='ans_del_bdid_".$q_id."_".$k."' value=''>");
					print("<input type='hidden' name='ans_bdid_".$q_id."_".$k."' value='".$ans_bdid."'>");
					print("<div id='q".$q_id."a".$k."'><input style='width:30px;' type='text' value='".$q_link."' name='quest_".$q_id."_ans_".$k."_link'>");
					print("<input type='text' value='".$ans_text."' id='quest_".$q_id."_ans_".$k."' name='quest_".$q_id."_ans_".$k."'><a onClick='del_ans(".$q_id.",".$k.");'>-X-</a></div>");
					$k++;
				}
			print("</div></td><td><div id='i".$q_n."'>");
				$inf_k = 1;
				while($inf_data = mysql_fetch_array($inf_q)){
					$inf_text = $inf_data['text'];
					$inf_bdid = $inf_data['id'];
					$inf_name = $inf_data['inf_name'];
					print("<input type='hidden' id='inf_del_bdid_".$q_id."_".$inf_k."' name='inf_del_bdid_".$q_id."_".$inf_k."' value=''>");
					print("<input type='hidden' name='inf_bdid_".$q_id."_".$inf_k."' value='".$inf_bdid."'>");
					print("<textarea id='quest_".$q_id."_inf_".$inf_k."' name='quest_".$q_id."_inf_".$inf_k."'>".$inf_name."###".$inf_text."</textarea>");
					print("<a id='quest_".$q_id."_inf_".$inf_k."' onClick='del_inf(".$q_id.",".$inf_k.")'>-X-</a>");
					$inf_k++;
				}
			print("</div></td></tr></table><hr></div>");
		}
	?>
</div>
</form>


</body>


</html>
















