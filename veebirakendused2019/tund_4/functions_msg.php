<?php
	function saveMsg($msgTitle, $message){
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vr_messages (msgTitle, message) VALUES (?, ?)");
		echo $conn->error;
		$stmt->bind_param("ss", $msgTitle, $message);
		$notice = null;
		if($stmt->execute()){
			$notice = "Mõtte salvestamine õnnestus!";
		} else {
			$notice = "Kahjuks tekkis tehniline viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function readAllMessages(){
		$msgHTML = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistan ette andmebaasipäringu
		$stmt = $conn->prepare("SELECT msgTitle, message FROM vr_messages");
		echo $conn->error;
		$stmt->bind_result($msgTitleFromDb, $messageFromDb);
		$stmt->execute();
		//$stmt->fetch();
		while($stmt->fetch()){
			$msgHTML .= "<h3>" .$msgTitleFromDb ."</h3> \n";
			$msgHTML .= "<p>" .$messageFromDb ."</p> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $msgHTML;
	}
	
