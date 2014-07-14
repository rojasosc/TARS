<?php
<<<<<<< HEAD
	if(isset($_POST['action'])){
		require('../db.php');
=======

// TODO: error handle x6
// TODO: session check x6

	if(isset($_POST['action'])){
		require_once '../db.php';
>>>>>>> origin/stage
		$action = $_POST['action'];
		switch($action){
			case 'fetchProfessor': 
				fetchProfessor();
				break;
			case 'updateProfessorProfile':
				updateProfessorProfile();
				break;
			case 'fetchBuildings':
				fetchBuildings();
				break;
			case 'fetchTheRooms':
				fetchTheRooms();
				break;
<<<<<<< HEAD
=======
			case 'fetchStudent':
				fetchStudent();
				break;
>>>>>>> origin/stage
			case 'newStudentComment':
				newStudentComment();
				break;
		}
	}else{
		echo "Error: action not specified.";
	}
	
	function fetchProfessor(){
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
	}
	
	function updateProfessorProfile(){
		$professor = User::getUserByEmail($_POST['email'],PROFESSOR);
		$building = $_POST['building'];
		$room = $_POST['room'];
		$office = Place::getPlaceByBuildingAndRoom($building, $room);
		$officeID = $office->getPlaceID();
<<<<<<< HEAD
		$professor->updateProfile($_POST['firstName'],$_POST['lastName'],$officeID,$_POST['officePhone'],$_POST['mobilePhone']);	
	}
	
	function fetchBuildings(){
		$buildings = Place::getBuildings();
=======
		$professor->updateProfile($_POST['firstName'],$_POST['lastName'],$officeID,$_POST['officePhone']);	
	}
	
	function fetchBuildings(){
		$buildings = Place::getAllBuildings();
>>>>>>> origin/stage
		echo json_encode($buildings);	
	}
	
	function fetchTheRooms(){
		$places = Place::getPlacesByBuilding($_POST['building']);
		$rooms = array();
		foreach($places as $room){
			$rooms[] = $room->getRoom();
		}
		echo json_encode($rooms);	
	}

	function newStudentComment(){
<<<<<<< HEAD
		Feedback::newComment($_POST['studentID'],$_POST['commenterID'],$_POST['comment']);
	}
?>
=======
		$student = User::getUserByID($_POST['studentID'], STUDENT);
		$student->saveComment($_POST['comment'], User::getUserByID($_POST['commenterID'],
			PROFESSOR), time());
	}

	function fetchStudent(){

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
			
			$staffComments = Feedback::getCommentsFromStaff($_POST['userID']);
			$professorComments = Feedback::getCommentsFromProfessors($_POST['userID']);
			/* Prepare to encode JSON object */
			$student = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'mobilePhone' => $mobilePhone,
			'classYear' => $classYear,'major' => $major,'gpa' => $gpa,'aboutMe' => $aboutMe, 'staffComments' => $staffComments, 'professorComments' => $professorComments);
			echo json_encode($student,true);
		}else{
			echo json_encode(false);
		}	
	}	
?>
>>>>>>> origin/stage
