<?php
	require("functions_main.php");
	//require("functions_daygrade.php");
	
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
	$target_dir = "../picuploads/";
	$target_normal_dir = "normal/";
	$target_original_dir = "original/";
	$target_file = null;
	$imageFileType = null;
	
	if (isset($_POST["photoSubmit"])){
		//var_dump($_FILES);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
		echo $imageFileType;
		//genereerime failile unikaalse ajatempliga nime
		$timeStamp = microtime(1) * 10000;
		$fileName = "vr_" .$timeStamp;
		
		//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		//$target_file = $target_dir .$fileName ."." .$imageFileType;
		$target_file = $fileName ."." .$imageFileType;
		
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				//$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 2097152) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			//teen väiksema koopia
			//loome "pildiobjekti"
			if($imageFileType == "jpg" or $imageFileType == "jpeg"){
				$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
			}
			if($imageFileType == "png"){
				$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
			}
			if($imageFileType == "gif"){
				$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
			}
			//pildi originaalmõõdud
			$imageWidth = imagesx($myTempImage);
			$imageHeight = imagesy($myTempImage);
			
			$imageSizeRatio = $imageWidth / 600; //teeme pildi 600 piksli laiuseks
			$newWidth = $imageWidth / $imageSizeRatio;
			$newHeight = $imageHeight / $imageSizeRatio;
			//nüüd siis reaalne suuruse muutus, loome uue pildiobjekti juba uute mõõtudega
			$myNewImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
			//vähendatud pilt salvestada
			if($imageFileType == "jpg" or $imageFileType == "jpeg"){
				if(imagejpeg($myNewImage, $target_dir . $target_normal_dir . $target_file, 90)){
					echo "Normaalfaili salvestamine õnnestus!";
				} else {
					echo "Normaalfaili salvestamine ei õnnestunud!";
				}
			}
			if($imageFileType == "png"){
				if(imagepng($myNewImage, $target_dir . $target_normal_dir . $target_file, 6)){
					echo "Normaalfaili salvestamine õnnestus!";
				} else {
					echo "Normaalfaili salvestamine ei õnnestunud!";
				}
			}
			if($imageFileType == "gif"){
				if(imagegif($myNewImage, $target_dir . $target_normal_dir . $target_file)){
					echo "Normaalfaili salvestamine õnnestus!";
				} else {
					echo "Normaalfaili salvestamine ei õnnestunud!";
				}
			}
			
			
			//kopeerin originaalfaili
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $target_original_dir . $target_file)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
			
			imagedestroy($myTempImage);
			imagedestroy($myNewImage);
			
		}
	}
	
	function resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight){
		$newImage = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($newImage, $myTempImage, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
		return $newImage;
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
	<h1>Veebirakendus, foto üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<p>Tagasi <a href="main.php">avalehele</a>!</p>
	<hr>
	<h2>Lae foto üles</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label>Vali üleslaetav pildifail (max 2MB): </label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		
		<br>
		  <label>Alt tekst: </label><input type="text" name="altText">
		  <br>
		  <label>Privaatsus</label>
		  <br>
		  <label><input type="radio" name="privacy" value="1">Avalik</label>&nbsp;
		  <label><input type="radio" name="privacy" value="2">Sisseloginud kasutajatele</label>&nbsp;
		  <label><input type="radio" name="privacy" value="3" checked>Isiklik</label>
		  <br>		
		
		<input name="photoSubmit" type="submit" value="Lae üles!">
	</form>
	<p><?php echo $notice; ?></p>
	
</body>
</html>






