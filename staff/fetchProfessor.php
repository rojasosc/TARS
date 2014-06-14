<?php
	ini_set('display_errors',1);
	include('../db.php');
	$user = User::getUserByID($_POST['userID']);
	$professor = array();
	if($user){
		$firstName = $user->getFirstName();
		$lastName = $user->getLastName();
		$email = $user->getEmail();
		$mobilePhone = $user->getMobilePhone();
		$officePhone = $user->getOfficePhone();
		
		/*TODO: Need to fix the Professor::getOffice() to return a place object */
		
		$officeID = $user->getOfficeID();
		$office = Place::getPlaceByID($officeID);
		$building = $office->getBuilding();
		$room = $office->getRoom();

		/* Prepare to encode JSON object */
		$professor = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'mobilePhone' => $mobilePhone,'officePhone' => $officePhone,'building' => $building,
		'room' => $room);
		echo json_encode($professor,true);
	}else{
		echo json_encode(false);
	}
	
?>