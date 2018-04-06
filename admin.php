<html>
<head>
<title>Конструктор документов v1</title>
<link type="text/css" href="style.css" rel="stylesheet" />
<script src="libs/jquery.min.js"></script>
<link rel="stylesheet" href="libs/themes/default/style.min.css" />
<script src="libs/jstree.min.js"></script>
<link href="libs/bs3/css/bootstrap.min.css" rel="stylesheet">
//
<script type="text/javascript" src="func.js"></script>
</head>
<?php
include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}
?>
<script>

function clear_mess(){
	$("#messages").html('');
}

function load_docs(){
	var catids = $("#hid_cats_id").val();
	$.ajax({
		type: 'POST',
		url: 'acts/printdocs.php',
		data: 'cats='+catids,
		success: function(data){
			$('#docs').html(data);
		}
	});
}
function deldoc(id){
	$.ajax({
		type: 'GET',
		url: 'acts/delins.php',
		data: 'id='+id,
		success: function(data){
			$('#messages').html(data);
			load_docs();
			setTimeout(clear_mess, 1500);
		}
	});
}
</script>

<div id="wrap">
<div id="messages"></div>
<form action="acts/addcat.php" method="POST" />
<div id="var_edit">
<center><h3>Редактор категорий</h3></center>
<hr>
Добавить категорию:
<input type="text" name="catname" /><br><br>
Родительская категория:
<select name="rod_cat_id">
<option value='0'>Нет родительской</option>
<?php
include("acts/bd.php");
?>

<?php 
function ShowTree2($ParentID, $lvl) { 

	global $lvl;
	$lvl++; 

	$sSQL="SELECT `id`,`name`,`parent_id` FROM `cats` WHERE `parent_id`=".$ParentID." ORDER BY `name`";
	$result=mysql_query($sSQL);

	if (mysql_num_rows($result) > 0) {
		while ( $row = mysql_fetch_array($result) ) {
			$ID1 = $row["id"];
			print("<option value=".$ID1.">");
			for($i=0; $i<$lvl; $i++){
				print("-->");
			}
			print($row['name']);
			print("</option>");
			ShowTree2($ID1, $lvl); 
			$lvl--;
		}
	}
}

ShowTree2(0, 0);
?> 

</select>
<br><br>
<input type="submit" value="Добавить">
</form><hr>
 <div id="jstree_demo_div">
<?php 
function ShowTree($ParentID, $lvl) { 

	global $lvl; 
	$lvl++; 

	$sSQL="SELECT `id`,`name`,`parent_id` FROM `cats` WHERE `parent_id`=".$ParentID." ORDER BY `name`";
	$result=mysql_query($sSQL);

	if (mysql_num_rows($result) > 0) {
		print("<ul>");
		while ( $row = mysql_fetch_array($result) ) {
			$ID1 = $row["id"];
			print("<li id='".$ID1."'>");
			echo("<A HREF=\""."?ID=".$ID1."\">".$row["name"]."</A>");
			ShowTree($ID1, $lvl); 
			$lvl--;
		}
		print("</li>");
		print("</ul>");
		
	}
}
ShowTree(0, 0);
?> 
</div>
<br><hr>
<form action="acts/delcats.php" method="POST" />
<center><h3>Действия с категориями</h3></center><br>
<input type="hidden" id="hid_cats_id" name="cats_id" />
Что делать с документами в удаляемых категориях?
<select name="whattodo">
<option value="1">Удалить</option>
<option value="2">Перенести в "Без категории"</option>
<?php

	$perenq = mysql_query("SELECT * FROM `cats`");
	while($perdata = mysql_fetch_array($perenq)){
		$perid = $perdata['id'];
		if($perid!=1){
			$pertext = $perdata['name'];
			print("<option value=".$perid.">");
			print('Перенести в "'.$pertext.'"');
			print("</option>");
		}
	}
	
?>
</select><br><br>
<input type="submit" value="Удалить выбранные категории">
</form>

<script>

$('#jstree_demo_div')
  // listen for event
  .on('changed.jstree', function (e, data) {
    var i, j, r = [];
    for(i = 0, j = data.selected.length; i < j; i++) {
      r.push(data.instance.get_node(data.selected[i]).li_attr.id);
    }
    $("#hid_cats_id").val(r);
	load_docs();
  })

$(function () { $('#jstree_demo_div').jstree({
	"core" : {
		"check_callback" : true
	},
    "checkbox" : {
      "keep_selected_style" : false
    },
    "contextmenu":{         
						"items": function($node) {
							var tree = $("#jstree_demo_div").jstree(true);
							return {
								"Изменить название": {
								"separator_before": false,
								"separator_after": false,
								"label": "Изменить название",
								"action": function (obj) {
										  //$node = tree.jstree('create_node', $node);
										  //$('#jstree_div').jstree("edit", $node);
										tree.edit($node, null, function(node,status){
											update_cat(arguments[0]['text'], arguments[0]['id']);
										});
									}
								},
							}
						}
	},
    "plugins" : [ "contextmenu", "checkbox" ]
  });   
  });
  
  $('#jstree_demo_div').on('ready.jstree', function() {
    $("#jstree_demo_div").jstree("open_all");          
});
  //get_checked(full)
</script>
</div>

<div id="dog_edit">
<center><h3>Инструкции</h3></center>
<hr>
<center><a href="acts/exit.php"><button><font color="red">Выйти</font></button></a></center>
<br>
<center><a target="_blank" href="new.php"><button>Разработать новую инструкцию</button></a></center>
<hr>

<?php
$dq = mysql_query("SELECT * FROM `inses` ORDER BY `id` DESC LIMIT 10");
?>

<center>Для просмотра инструкций выберите нужную категорию в окне слева. <br><br></center>
<div id="docs">
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
</div>

</div>



</div>



</html>