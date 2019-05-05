<?php
	function saveDayGrade($grade){
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vr_grade_for_day (userid, grade, day) VALUES (?, ?, ?)");
		echo $conn->error;
		//ajutine kasutaja id
		$userid = 1;
		$today = new DateTime("now");
		$day = $today->format('Y-m-d');
		$stmt->bind_param("iis", $userid, $grade, $day);
		$notice = null;
		if($stmt->execute()){
			$notice = "Hinde salvestamine õnnestus!";
		} else {
			$notice = "Kahjuks tekkis tehniline viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function showAllDayGrades(){
		$dayGradeHTML = "";
		$finalHTML = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT day, grade FROM vr_grade_for_day");
		echo $conn->error;
		$stmt->bind_result($dayFromDB, $gradeFromDB);
		$stmt->execute();
		while ($stmt->fetch()){
			$dayGradeHTML .= "<li>" .$dayFromDB ." hinne: " .$gradeFromDB ."</li> \n";
		}
		$stmt->close();
		$conn->close();
		if (!empty($dayGradeHTML)){
			$finalHTML = "<ul> \n" .$dayGradeHTML ."\n </ul> \n";
		} else {
			$finalHTML = "<p>Kahjuks pole hindeid sisestatud!</p> \n";
		}
		return $finalHTML;
	}
	
	function showTodaysGrades(){
		$dayGradeHTML = "";
		$finalHTML = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT grade FROM vr_grade_for_day WHERE day = ?");
		echo $conn->error;
		$today = new DateTime("now");
		$day = $today->format('Y-m-d');
		$stmt->bind_param("s", $day);
		$stmt->bind_result($gradeFromDB);
		$stmt->execute();
		while ($stmt->fetch()){
			$dayGradeHTML .= "<li>Hinne: " .$gradeFromDB ."</li> \n";
		}
		$stmt->close();
		$conn->close();
		if (!empty($dayGradeHTML)){
			$finalHTML = "<ul> \n" .$dayGradeHTML ."\n </ul> \n";
		} else {
			$finalHTML = "<p>Kahjuks pole täna hindeid sisestatud!</p> \n";
		}
		return $finalHTML;
	}