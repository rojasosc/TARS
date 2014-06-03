<?php

	include('../db.php');
	 
	 foreach($_POST as $action){
		$IDs = explode(" ",$action);
		
		$status = $IDs[0];
		$studentID = $IDs[1];
		
 		echo $status . " " . $studentID . "<br>";
		
		if(!!$status){
		
			setStatus($studentID,$status);
		}
		
		
	 }
	 
	header('Location: verifyStudents.php');


?>