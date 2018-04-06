function add_quest(){
	var q = '"';
	var code = $("#questions").html();
	
	var quest_num = $("#quest_num").val();
	quest_num++;
	
	var to_add = "<div class='q"+quest_num+"'><table><tr><td>Вопрос - "+quest_num+"<br><textarea cols=40 rows=5 name='quest_"+quest_num+"' id='quest_"+quest_num+"'></textarea><br>";
	    to_add+= "<a onClick='add_ans_to("+q+quest_num+q+")'>Добавить ответ</a> | ";
		to_add+= "<input type='hidden' name='quest_"+quest_num+"_ans_num' id='quest_"+quest_num+"_ans_num' value='1'>";
		to_add+= "<input type='hidden' name='quest_"+quest_num+"_inf_num' id='quest_"+quest_num+"_inf_num' value='1'>";
		to_add+= "<a onClick='add_inf_to("+q+quest_num+q+")'>Добавить i</a> | ";
		//to_add+= "<a onClick='add_zan_to("+q+quest_num+q+")'>Добавить z</a> | ";
		to_add+= "<a onClick='del_quest("+q+quest_num+q+")'><font color='red'>Удалить</font></a>";
		to_add+= "<div id='anses_"+quest_num+"'></div></td><td><div id='i"+quest_num+"'></div></td></tr></table><hr></div>";
	
	$("#questions").append(to_add);
	
	$("#quest_num").val(quest_num);
}

function add_ans_to(quest_id){
	var ans_num = $("#quest_"+quest_id+"_ans_num").val();
	var to_add = "<div id='q"+quest_id+"a"+ans_num+"'><input style='width:30px;' type='text' name='quest_"+quest_id+"_ans_"+ans_num+"_link'>";
	    to_add+= "<input type='text' name='quest_"+quest_id+"_ans_"+ans_num+"'><a onClick='del_ans("+quest_id+","+ans_num+");'>-X-</a></div>";
	$("#anses_"+quest_id).append(to_add);
	ans_num++;
	$("#quest_"+quest_id+"_ans_num").val(ans_num);
}
function del_ans(qid, anid){
	$("#q"+qid+"a"+anid).remove();
	$("#ans_del_bdid_"+qid+"_"+anid).val("DEL");
	
}
function del_quest(quest_id){
	$(".q"+quest_id).remove();
	$("#quest_del_bdid_"+quest_id).val("DEL");
}

function add_inf_to(quest_id){
	var inf_num = $("#quest_"+quest_id+"_inf_num").val();
	
	var to_add = "<textarea id='quest_"+quest_id+"_inf_"+inf_num+"' name='quest_"+quest_id+"_inf_"+inf_num+"'>id"+inf_num+"###</textarea><a id='quest_"+quest_id+"_inf_"+inf_num+"' onClick='del_inf("+quest_id+","+inf_num+")'>-X-</a>";
	$("#i"+quest_id).append(to_add);
	
	inf_num++;
	$("#quest_"+quest_id+"_inf_num").val(inf_num);
}
function del_inf(quest_id, infid){
	$("#inf_del_bdid_"+quest_id+"_"+infid).val("DEL");
	$("#quest_"+quest_id+"_inf_"+infid).remove();
	$("#quest_"+quest_id+"_inf_"+infid).remove();
}

function update_cat(text, cat_id){
	$.ajax({
		url: "acts/update_cat.php",
		type: "POST",
		data: "cat_name="+text+"&cat_id="+cat_id,
		success:(function(html){
			alert(html);
		})
	});
}










