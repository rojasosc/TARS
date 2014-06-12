<?php
	ini_set("display_errors",1);
	include('../db.php');
	$usersFound = User::findUsers($_POST['emailSearch'],$_POST['firstName'],$_POST['lastName'],$_POST['searchType']);
	$usersTable = array();
	
	/* Add each user to the usersTable to be encoded into a JSON object */
	foreach($usersFound as $user){
		$firstName = $user->getFirstName();
		$lastName = $user->getLastname();
		$email = $user->getEmail();
		$userID = getUserID($email);
		$usersTable[] = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'userID' => $userID);
	}
	echo json_encode($usersTable);
?>