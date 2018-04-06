<?php

if(isset($_POST['login']) && isset($_POST['password'])){
	$login = $_POST['login'];
	$password = $_POST['password'];
	if($login=="admin" && $password=="samfile159"){
		session_start();
		$_SESSION['login']="admin";
		$_SESSION['md5']="passtrue";
		header("location: ../admin.php");
	}else{
		header("location: ../loginpage.php?error=wrongdata");
	}
}

?>