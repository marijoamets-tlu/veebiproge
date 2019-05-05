<?php
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
	$picsDir = "haapsalu_fotod/";
	$allFiles = array_slice(scandir($picsDir), 2);
	//echo $allFiles;
	//var_dump($allFiles);
	
	$picCount = count($allFiles);
	$picNum = mt_rand(0, ($picCount - 1));
	$picFile = $picsDir .$allFiles[$picNum];
	echo $picFile;
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
		
		//http://tigu.hk.tlu.ee/~andrus.rinde/veebirakendused2019/haapsalu_fotod/IMG_0175.JPG
		//   ../../~andrus.rinde/veebirakendused2019/haapsalu_fotod/IMG_0175.JPG
	?>
	<hr>
		<img src="haapsalu_fotod/IMG_0175.JPG" alt="pildike Haapsalust" width="600">
	<hr>
	<p>Tigu php serveri <a href="serverinfo.php" target="_blank">info</a>.</p>
</body>
</html>






