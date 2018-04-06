<html>

<head>
<title>Новая инструкция</title>
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
</head>
<?php
include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}
?>
<body>

<form method="POST" action="acts/add_new.php">
<input type="submit" value="Сохранить"><br>
<input type="hidden" name="quest_num" id="quest_num" value="0">

Наименование инструкции: <input type="text" name="ins_name">
Категория инструкции: 
<select name="ins_catid">
<?php
	include("acts/bd.php");
	$allcatsq = mysql_query("SELECT * FROM `cats`");
	while($cats_data = mysql_fetch_array($allcatsq)){
		$thiscatid = $cats_data['id'];
		$thiscatname = $cats_data['name'];
			print("<option value=".$thiscatid.">");
			print($thiscatname);
			print("</option>");
	}
?>
</select>
<div id="questions">
</div>
</form>


</body>


</html>