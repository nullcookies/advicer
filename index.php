<?php

session_start();

if(isset($_SESSION['login']) && $_SESSION['md5']){
		header("location: admin.php");
	}
	else{ 
		header("location: loginpage.php");
	}

?>