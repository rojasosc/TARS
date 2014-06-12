<?php
	ini_set('display_errors',1);
	include('../db.php');
	$user = User::getUserByID(/*$_POST['userID']*/14);
	$professor = array();
	if($user){
		$firstName = $user->getFirstName();
		$lastName = $user->getLastName();
		$email = $user->getEmail();
		$mobilePhone = $user->getMobilePhone();
		$officePhone = $user->getOfficePhone();
		
		/*TODO: Need to fix the Professor::getOffice() to return a place object */
		
		/* $office = $user->getOffice();
		NOTE: Returns NULL */
		
		//$building = $office->getBuilding();
		//$room = $office->getRoom();

		/* Prepare to encode JSON object */
		$professor = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'mobilePhone' => $mobilePhone,'officePhone' => $officePhone);
		echo json_encode($professor,true);
	}else{
		echo json_encode(false);
	}
	
?>