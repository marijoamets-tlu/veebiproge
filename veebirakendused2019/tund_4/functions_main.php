<?php
	require("../../../config.php");
	$database = "andrusrinde";
	
	//võtan kasutusele sessiooni
	session_start();
	
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	function signup($name, $surname, $email, $gender, $birthDate, $password){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//kõigepealt kontrollime, ega pole sellist kasutajat olemas
		$stmt = $conn->prepare("SELECT id FROM vr_users WHERE email=?");
		echo $conn->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($idFromDb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Kahjuks on sellise kasutajanimega (" .$email .") kasutaja juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vr_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
			echo $conn->error;
			
			$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
			$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
			
			$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
			if($stmt->execute()){
				$notice = "OK!";
			} else {
				$notice = "Error: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function signin($email, $password){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT id, firstname, lastname, password FROM vr_users WHERE email=?");
		echo $conn->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
		if($stmt->execute()){
			if($stmt->fetch()){
				if(password_verify($password, $passwordFromDb)){
					$notice = "Palju õnne!";
					$_SESSION["userid"] = $idFromDb;
					$_SESSION["userfirstname"] = $firstnameFromDb;
					$_SESSION["userlastname"] = $lastnameFromDb;
					
					$stmt->close();
					
					//kasutajaprofiili lugemine
					$stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vr_userprofiles WHERE userid=?");
					echo $conn->error;
					$stmt->bind_param("i", $_SESSION["userid"]);
					$stmt->bind_result($bgcolor, $txtcolor);
					$stmt->execute();
					if($stmt->fetch()){
						$_SESSION["bgcolor"] = $bgcolor;
						$_SESSION["txtcolor"] = $txtcolor;
					} else {
						$_SESSION["bgcolor"] = "#FFFFFF";
						$_SESSION["txtcolor"] = "#000000";
					}
					$stmt->close();
					$conn->close();
					
					header("Location: main.php");
					exit();
					
				} else {
					$notice = "Vale salasõna!";
				}
			} else {
				$notice = "Kahjuks sellist kasutajat (" .$email .") ei leitud!";
			}
		} else {
			$notice = "Sisselogimisel tekkis tehniline viga! " .$stmt->error;
		}
			
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	
  //kasutajaprofiili salvestamine
  function storeuserprofile($desc, $bgcol, $txtcol){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT description, bgcolor, txtcolor FROM vr_userprofiles WHERE userid=?");
	echo $conn->error;
	$stmt->bind_param("i", $_SESSION["userid"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil juba olemas, uuendame
		$stmt->close();
		$stmt = $conn->prepare("UPDATE vr_userprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		echo $conn->error;
		$stmt->bind_param("sssi", $desc, $bgcol, $txtcol, $_SESSION["userid"]);
		if($stmt->execute()){
			$notice = "Profiil edukalt uuendatud!";
			$_SESSION["bgcolor"] = $bgcol;
		    $_SESSION["txtcolor"] = $txtcol;
		} else {
			$notice = "Profiili uuendamisel tekkis tõrge! " .$stmt->error;
		}
	} else {
		//profiili pole, salvestame
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO vr_userprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("isss", $_SESSION["userid"], $desc, $bgcol, $txtcol);
		if($stmt->execute()){
			$notice = "Profiil edukalt salvestatud!";
			$_SESSION["bgcolor"] = $bgcol;
		    $_SESSION["txtcolor"] = $txtcol;
		} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
	return $notice;
  }
  
  //kasutajaprofiili väljastamine
  function showmyprofiledesc(){
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT description FROM vr_userprofiles WHERE userid=?");
	echo $conn->error;
	$stmt->bind_param("i", $_SESSION["userid"]);
	$stmt->bind_result($descriptionFromDb);
	$stmt->execute();
	$description = "";
	if($stmt->fetch()){
		$description = $descriptionFromDb;
	}
	$stmt->close();
	$conn->close();
	return $description;
  }
  
  //kasutajaprofiili väljastamine
  function readprofilecolors(){
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT description, bgcolor, txtcolor FROM vr_userprofiles WHERE userid=?");
	echo $conn->error;
	$stmt->bind_param("i", $_SESSION["userid"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	$profile = new Stdclass();
	if($stmt->fetch()){
		$profile->description = $description;
		$profile->bgcolor = $bgcolor;
		$profile->txtcolor = $txtcolor;
	} else {
		$profile->description = "";
		$profile->bgcolor = "";
		$profile->txtcolor = "";
	}
	$stmt->close();
	$conn->close();
	return $profile;
  }
	
	
	
	