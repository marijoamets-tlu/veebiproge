<?php
	require("functions_main.php");
	
	//kui pole sisseloginud
	if(!isset($_SESSION["userid"])){
		header("Location: index_main.php");
		exit();
	}
	
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: index_main.php");
		exit();
	}
	
	//var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Andrus Rinde, Veebirakendused ja nende loomine 2019</title>
	<style>
		body {
			background-color: <?php echo $_SESSION["bgcolor"]; ?>;
			color: <?php echo $_SESSION["txtcolor"]; ?>;
		}
	</style>
</head>
<body>
	<h1>Sisseloginute pealeht</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<ul>
		<li><b><a href="?logout=1">Logi välja!</a></b></li>
		<li>Loo/muuda <a href="userprofile.php">kasutajaprofiili</a></li>
		<li>Anna tänasele päevale <a href="gradetoday.php">hinne</a></li>
		<li>Lae foto <a href="photoupload.php">üles</a></li>
	</ul>
	
</body>
</html>






