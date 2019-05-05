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
	
  $mybgcolor = $_SESSION["bgcolor"];
  $mytxtcolor = $_SESSION["txtcolor"];
  
  if(isset($_POST["submitProfile"])){
	$notice = storeuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"]);
	if(!empty($_POST["description"])){
	  $mydescription = $_POST["description"];
	}
	$mybgcolor = $_POST["bgcolor"];
	$mytxtcolor = $_POST["txtcolor"];
  } else {
	$mydescription = showmyprofiledesc();
	if($mydescription == ""){
	  $mydescription = "Pole tutvustust lisanud!";
    }
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
	<h1>Kasutajaprofiil</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu kirjeldus</label><br>
	  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
	  <br>
	  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	  <input name="submitProfile" type="submit" value="Salvesta profiil">
	</form>
	<p><a href="main.php">Tagasi</a> avalehele!</p>
	
</body>
</html>






