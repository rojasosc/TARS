<?php
	include('../db.php');
	$user = User::getUserByID($_POST['userID']);
	$student = array();
	if($user){
		$firstName = $user->getFirstName();
		$lastName = $user->getLastName();
		$email = $user->getEmail();
		$mobilePhone = $user->getMobilePhone();
		$classYear = $user->getClassYear();
		$major = $user->getMajor();
		$gpa = $user->getGPA();
		$aboutMe = $user->getAboutMe();
		
		/* Prepare to encode JSON object */
		$student = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'mobilePhone' => $mobilePhone,
		'classYear' => $classYear,'major' => $major,'gpa' => $gpa,'aboutMe' => $aboutMe);
		echo json_encode($student,true);
	}else{
		echo json_encode(false);
	}
	
?>