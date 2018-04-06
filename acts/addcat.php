<?php

include("functions.php");
if(!logined()){
	header("location: loginpage.php");
}

include("bd.php");

$cat_name = $_POST['catname'];
$rod_cat_id = $_POST['rod_cat_id'];

$q = mysql_query("INSERT INTO `cats` (`name`, `parent_id`) VALUES ('$cat_name','$rod_cat_id')");

header("location: ../admin.php");

?>