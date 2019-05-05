<?php
	require("../../../config.php");
	$database = "andrusrinde";
	
	//error_reporting(E_ALL);
	//echo "Selles serveris kasutatakse PHP-d versiooniga: " .phpversion();
	$phpVersionNotice = "Selles serveris kasutatakse PHP-d versiooniga: " .phpversion();
	//echo phpinfo();
	//$phpServerInfo = phpinfo();
	
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
	$semesterFromStart = $semesterStart->diff($today);
	
	//loeme kataloogist failide nimekirja ja valime juhusliku pildi
	$picsDir = "../haapsalu_fotod/";
	$picFileTypes = ["image/jpeg", "image/png", "image/gif"];
	$allFiles = array_slice(scandir($picsDir), 2);
	//var_dump($allFiles);
	$picFiles = [];
	$picsToShow = [];
	
	foreach ($allFiles as $file){
		$fileInfo = getimagesize($picsDir .$file);
		if (in_array($fileInfo["mime"], $picFileTypes) == true){
			array_push($picFiles, $file);
		}
	}
	
	//var_dump($picFiles);
	
	$picCount = count($picFiles);
	for ($i = 0; $i < 3; $i ++){
		do {
			$picNum = mt_rand(0, ($picCount - 1));
		} while (in_array($picNum, $picsToShow) == true);
		array_push($picsToShow, $picNum);
	}
	//var_dump($picsToShow);
	//$picFile = $picsDir .$picFiles[$picNum];
	$htmlForImages = "";
	for($i = 0; $i < 3; $i++){
		$htmlForImages .= '<img src="';
		$htmlForImages .= $picsDir .$picFiles[$picsToShow[$i]];
		$htmlForImages .= '" alt="pildike Haapsalust" width="600">' ."\n";
	}
	
	$msgHTML = "";
	readAllMessages();
	function readAllMessages(){
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistan ette andmebaasipäringu
		$stmt = $conn->prepare("SELECT msgTitle, message FROM vr_messages");
		echo $conn->error;
		$stmt->bind_result($msgTitleFromDb, $messageFromDb);
		$stmt->execute();
		//$stmt->fetch();
		while($stmt->fetch()){
			$GLOBALS["msgHTML"] .= "<h3>" .$msgTitleFromDb ."</h3> \n";
			$GLOBALS["msgHTML"] .= "<p>" .$messageFromDb ."</p> \n";
		}
		
		$stmt->close();
		$conn->close();
	}
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Andrus Rinde, Veebirakendused ja nende loomine 2019</title>
</head>
<body>
	<h1>Andrus Rinde</h1>
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
		<?php echo $htmlForImages; ?>
	<hr>
	<h2>Kasutajate jäetud head mõtted</h2>
	<div><?php echo $msgHTML; ?></div>
	<p>Tigu php serveri <a href="serverinfo.php" target="_blank">info</a>.</p>
</body>
</html>






