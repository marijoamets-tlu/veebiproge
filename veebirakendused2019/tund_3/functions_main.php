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
	
	
	
	