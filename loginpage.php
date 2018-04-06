<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Вход в конструктор инструкций v1</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>

	<?php
		if(isset($_GET['error']) && $_GET['error']=="wrongdata"){
			$fal = 1;
		}else $fal=0;
	?>

	<div id="container"><br>
	<center><h1>Вход в редактор инструкций</h1></center><hr>
		<form action="acts/login.php" method="POST">  
		<br>
			<label for="name">Логин:</label>
			<input type="name" name="login">
			<label for="username">Пароль:</label>
			<input type="password" name="password">
			<div id="lower">
				<input type="submit" value="Войти"><br><br>
			</div>
		</form><br>
		<?php
		if($fal==1){
			?>
		<center><h4><font color="red"><b>Неверный логин или пароль. Проверьте введенные данные.</b></font></h4></center>
		<?php
		}
		?>
	</div>
</body>
</html>