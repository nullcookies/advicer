<?php

session_start();

function logined(){
	if(isset($_SESSION['login']) && $_SESSION['md5']){
		return true;
	}
	else{ 
		return false;
	}
}

?>