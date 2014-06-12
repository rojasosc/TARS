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

		/* Prepare to encode JSON object */
		$professor = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'mobilePhone' => $mobilePhone);
		echo json_encode($professor,true);
	}else{
		echo json_encode(false);
	}
	
?>