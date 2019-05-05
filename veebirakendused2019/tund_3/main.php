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
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Andrus Rinde, Veebirakendused ja nende loomine 2019</title>
</head>
<body>
	<h1>Sisseloginute pealeht</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<p><b><a href="?logout=1">Logi välja!</a></b></p>
	
</body>
</html>






