<?php


if(isset($_POST['cat_name']) && isset($_POST['cat_id'])){

	include("bd.php");

	$cat_name = $_POST['cat_name'];
	$cat_id = $_POST['cat_id'];

	$q = mysql_query("UPDATE `cats` SET `name`='$cat_name' WHERE `id`='$cat_id' ");

	if($q){
		print("Успешно обновлено!");
	}else{
		print("Ошибка при обновлении!");
	}
}

?>