<?php
	require("functions_main.php");
	require("functions_daygrade.php");
	
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
		
	$notice = null;
	$grade = null;
		
	if (isset($_POST["gradeSubmit"])){
		$notice = saveDayGrade($_POST["dayGrade"]);
	}

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
	<h1>Veebirakendus, päeva hinne</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<p>Tagasi <a href="main.php">avalehele</a>!</p>
	<hr>
	<h2>Päevale hinde andmine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Hinne (10 palli süsteemis): </label>
		<input name="dayGrade" id="dayGrade" type="number" min="0" max="10" value="10">
		<br>
		<input name="gradeSubmit" type="submit" value="Sisesta hinne!">
	</form>
	<p><?php echo $notice; ?></p>
	
</body>
</html>





