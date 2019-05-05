<?php
	require("functions_main.php");
	require("functions_msg.php");
		
	$notice = null;
	$msgTitle = null;
	$message = null;
	
	if (isset($_POST["msgSubmit"])){
		if(!empty($_POST["msgTitle"])){
			$msgTitle = test_input($_POST["msgTitle"]);
		} else {
			$notice = "Mõtte pealkiri on puudu! ";
		}
		if(!empty($_POST["message"])){
			$message = test_input($_POST["message"]);
		} else {
			$notice .= "Mõte ise on puudu!";
		}
		
		if(empty($notice)){
			$notice = saveMsg($msgTitle, $message);
		}
	}
	
	if($notice == "Mõtte salvestamine õnnestus!"){
		$msgTitle = null;
		$message = null;
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Andrus Rinde, Veebirakendused ja nende loomine 2019</title>
</head>
<body>
	<h1>Veebirakendus, hea mõte</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<p>Tagasi <a href="index_main.php">avalehele</a>!</p>
	<hr>
	<h2>Päevakajalise mõtte lisamine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Mõtte pealkiri (max 24 tähemärki): </label>
		<input name="msgTitle" id="msgTitle" type="text" value="<?php
			if (!empty($notice) and !empty($msgTitle)){
				echo $msgTitle;
			} else {
				echo "";
			}
		?>">
		<br>
		<label>Siia sisestage palun oma mõte (max 256 tähemärki)!</label>
		<br>
		<textarea rows="4" cols="64" id="message" name="message" placeholder="Minu mõte ..."><?php
			if (!empty($notice) and !empty($message)){
				echo $message;
			}
		?></textarea>
		<br>
		<input name="msgSubmit" type="submit" value="Salvesta oma mõte!">
	</form>
	<p><?php echo $notice; ?></p>
</body>
</html>






