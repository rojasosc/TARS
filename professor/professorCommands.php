<?php
	if(isset($_POST['action'])){
		require('../db.php');
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
		$professor->updateProfile($_POST['firstName'],$_POST['lastName'],$officeID,$_POST['officePhone'],$_POST['mobilePhone']);	
	}
	
	function submitSelections(){}
	
	function fetchBuildings(){
		$buildings = Place::getBuildings();
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
?>