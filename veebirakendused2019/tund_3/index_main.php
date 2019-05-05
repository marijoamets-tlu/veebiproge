<?php
	require("functions_main.php");
	require("functions_msg.php");
	require("functions_daygrade.php");
	
	  $notice = "";
	  $email = "";
	  $emailError = "";
	  $passwordError = "";
	  
	  if(isset($_POST["login"])){
			if (isset($_POST["email"]) and !empty($_POST["email"])){
			  $email = test_input($_POST["email"]);
			} else {
			  $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
			}
		  
			if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
			  $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
			}
		  
		  if(empty($emailError) and empty($passwordError)){
			 $notice = signin($email, $_POST["password"]);
			 } else {
			  $notice = "Ei saa sisse logida!";
		  }
	  }
	
	$phpVersionNotice = "Selles serveris kasutatakse PHP-d versiooniga: " .phpversion();
	
	//uurime, mis kell on ja hindame päeva osa
	$fullTimeNow = date("d.m.Y H:i:s");
	$hourNow = date("H");
	$partOfDay = "Hägune aeg.";
	if($hourNow < 10){
		$partOfDay = "Liiga vara akadeemiliselt aktiivne olemiseks.";
	}
	if($hourNow >= 10 and $hourNow < 18){
		$partOfDay = "Sobiv aeg akadeemiliseks aktiivsuseks.";
	}
	if($hourNow >= 18 and $hourNow < 22){
		$partOfDay = "Vaba aeg.";
	}
	if($hourNow > 22){
		$partOfDay = "Uneaeg.";
	}
	
	//selgitan välja selle semestri pikkuse
	$semesterStart = new DateTime("2019-1-31");
	$semesterEnd = new DateTime("2019-5-24");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	$today = new DateTime("now");
	/*$nextweek = new DateTime("now");
	$nextweek->add(new DateInterval('P7D'));
	$prevweek = new DateTime("now");
	$prevweek->sub(new DateInterval('P7D'));
	echo "Eelmine nädal: " .$prevweek->format('Y-m-d');
	echo " Täna: " .$today->format('Y-m-d');
	echo " Järgmine nädal: " .$nextweek->format('Y-m-d');*/
	$semesterFromStart = $semesterStart->diff($today);
	
	//loeme kataloogist failide nimekirja ja valime juhusliku pildi
	$picsDir = "../haapsalu_fotod/";
	$picFileTypes = ["image/jpeg", "image/png", "image/gif"];
	$allFiles = array_slice(scandir($picsDir), 2);
	$picFiles = [];
	$picsToShow = [];
	
	foreach ($allFiles as $file){
		$fileInfo = getimagesize($picsDir .$file);
		if (in_array($fileInfo["mime"], $picFileTypes) == true){
			array_push($picFiles, $file);
		}
	}
		
	$picCount = count($picFiles);
	for ($i = 0; $i < 3; $i ++){
		do {
			$picNum = mt_rand(0, ($picCount - 1));
		} while (in_array($picNum, $picsToShow) == true);
		array_push($picsToShow, $picNum);
	}
	
	$htmlForImages = "";
	for($i = 0; $i < 3; $i++){
		$htmlForImages .= '<img src="';
		$htmlForImages .= $picsDir .$picFiles[$picsToShow[$i]];
		$htmlForImages .= '" alt="pildike Haapsalust" width="600">' ."\n";
	}
	
	$msgHTML = readAllMessages();
	//$dayGradesHTML = showAllDayGrades();
	$dayGradesHTML = showTodaysGrades();
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Andrus Rinde, Veebirakendused ja nende loomine 2019</title>
</head>
<body>
	<h1>Veebirakendus</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<?php
		echo "<p>Lehe avamise hetkel oli aeg: " .$fullTimeNow .".</p>";
		echo '<p>Tudengi seisukohalt on: "' .$partOfDay .'".</p>';
		echo "<p>2019 kevadsemestri õppetöö periood RIF18 üliõpilastele kestab " .$semesterDuration->format("%r%a") ." päeva, tänaseks on möödunud juba " .$semesterFromStart->format("%r%a")." päeva.</p>";
		echo '<meter min="0" max="' .$semesterDuration->format("%r%a").'" value="' .$semesterFromStart->format("%r%a") .'">' .round(($semesterFromStart->format("%r%a")/$semesterDuration->format("%r%a")) * 100, 2) .'%</meter>';
		echo "<p>" .$phpVersionNotice ."</p>";
	?>
	<hr>
	<h2>Valikud</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		
		<label>Kasutajatunnus (e-mail):</label>
		<input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
	    <label>Salasõna (min 8 tähemärki):</label>
	    <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
		<input name="login" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
	  
	</form>
	<ul>
		<li>Anna tänasele päevale <a href="gradetoday.php">hinne</a>!</li>
	</ul>
	<hr>
		<?php echo $htmlForImages; ?>
	<hr>
	<h2>Head mõtted</h2>
	<p>Salvesta oma hea mõte <a href="addmsg.php">siin</a>!</p>
	<hr>
	<h2>Kasutajate jäetud head mõtted</h2>
	<div><?php echo $msgHTML; ?></div>
	<hr>
		<h2>Külastajate antud hinded päevadele</h2>
		<?php echo $dayGradesHTML;?>
	<hr>
	<p>Tigu php serveri <a href="serverinfo.php" target="_blank">info</a>.</p>
</body>
</html>






