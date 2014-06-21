<?php
	/* Check if an action has been specified, otherwise
	exit. */
	if(isset($_POST['action'])){
		require('../db.php');
		$action = $_POST['action'];
		switch($action){
			case 'createProfessor' : 
				createProfessor();
				break;
			case 'updateProfessorProfile':
				updateProfessorProfile();
				break;
			case 'updateStudentProfile':
				updateStudentProfile();
				break;
			case 'updateStudentStatus':
				updateStudentStatus();
				break;
			case 'fetchProfessor':
				fetchProfessor();
				break;
			case 'fetchProfessors':
				fetchProfessors();
				break;
			case 'fetchStudent':
				fetchStudent();
				break;
			case 'newStudentComment':
				newStudentComment();
				break;	
			case 'fetchCourses':
				fetchCourses();
				break;
			case 'fetchBuildings':
				fetchBuildings();
				break;
			case 'fetchTheProfessors':
				fetchTheProfessors();
				break;
			case 'fetchTheRooms':
				fetchTheRooms();
				break;
			case 'searchForUsers':
				searchForUsers();
				break;
			case 'preparePayrollDownload':
				preparePayrollDownload();
				break;
		}
	}else{
		/*TODO: Add error handling */
		echo "action not specified";
	}
	
	function createProfessor(){
		$office = Place::getPlaceByBuildingAndRoom($_POST['building'],$_POST['room']);	
		$officeID = $office->getPlaceID();
		Professor::registerProfessor($_POST['email'], $password_hash, $_POST['firstName'], $_POST['lastName'],
			$officeID, $_POST['officePhone'], $_POST['mobilePhone']);
			
	}
	
	function updateProfessorProfile(){
		$professor = User::getUserByEmail($_POST['email'],PROFESSOR);
		$building = $_POST['building'];
		$room = $_POST['room'];
		$office = Place::getPlaceByBuildingAndRoom($building, $room);
		$officeID = $office->getPlaceID();
		$professor->updateProfile($_POST['firstName'],$_POST['lastName'],$officeID,$_POST['officePhone'],$_POST['mobilePhone']);
		
	}
	
	function updateStudentProfile(){
		$student = User::getUserByEmail($_POST['email'],STUDENT);
		$student->updateProfile($_POST['firstName'],$_POST['lastName'],$_POST['mobilePhone'],$_POST['major'],$_POST['classYear'],$_POST['gpa'],$_POST['aboutMe']);
		
	}
	
	function updateStudentStatus(){
		Student::setStudentStatus($_POST['userID'],$_POST['status']);
	
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
	
	function fetchProfessors(){
		$professors = Professor::getAllProfessors();
		$profArray = array();
		foreach($professors as $professor){
			$profArray[] = array('firstName' => $professor->getFirstName(), 'lastName' => $professor->getLastName());
		}
		echo json_encode($profArray,true);
		
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
			
			/* Prepare to encode JSON object */
			$student = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'mobilePhone' => $mobilePhone,
			'classYear' => $classYear,'major' => $major,'gpa' => $gpa,'aboutMe' => $aboutMe);
			echo json_encode($student,true);
		}else{
			echo json_encode(false);
		}	
	}

	function newStudentComment(){
		Feedback::newComment($_POST['studentID'],$_POST['commenterID'],$_POST['comment']);
	}
	
	function fetchCourses(){
		$courses = Course::getAllCourses();
		echo json_encode($courses,true);	
		
	}
	
	function fetchBuildings(){
		$buildings = Place::getBuildings();
		echo json_encode($buildings);	
	}
	
	function fetchTheProfessors(){
		$courseTitle = $_POST['courseTitle'];
		$professors = Course::getCourseProfessors($courseTitle);
		echo json_encode($professors,true);	
	}
	
	function fetchTheRooms(){
		$places = Place::getPlacesByBuilding($_POST['building']);
		$rooms = array();
		foreach($places as $room){
			$rooms[] = $room->getRoom();
		}
		echo json_encode($rooms);	
	}
	
	function searchForUsers(){
		$usersFound = User::findUsers($_POST['email'],$_POST['firstName'],$_POST['lastName'],$_POST['searchType']);
		$usersTable = array();
		
		/* Add each user to the usersTable to be encoded into a JSON object */
		foreach($usersFound as $user){
			$firstName = $user->getFirstName();
			$lastName = $user->getLastname();
			$email = $user->getEmail();
			$userID = $user->getID();
			$usersTable[] = array('firstName' => $firstName,'lastName' => $lastName,'email' => $email,'userID' => $userID);
		}
		echo json_encode($usersTable);
	}
	
	function preparePayrollDownload(){
		$term = Term::getTermByID(CURRENT_TERM);
		$fileName = "payroll-{$term->getYear()}-{$term->getSession()}.xls";
		$assistants = Application::getApplications(null, null, $term, APPROVED, 'pay');
		header("Content-Type: application/vnd.ms-excel");
		
		/* Table header */
		echo 'University ID'. "\t". 'First Name' . "\t" . 'Last Name' . "\t" . 'Email' . "\t" .'CRN' . "\t" . 'Type' . "\t" . 'Class Year' . "\t" . 'Compensation' . "\n";

		/* Insert each position into the spreadsheet */
		foreach($assistants as $assistant){
			$student = $assistant->getStudent();
			$position = $assistant->getPosition();
			$course = $position->getCourse();
			
			/* Column values */
			$universityID = $student->getUniversityID();
			$firstName = $student->getFirstName();
			$lastName = $student->getLastName();
			$email = $student->getEmail();
			$crn = $course->getCRN();
			$type = $position->getPositionType();
			$classYear = $student->getClassYear();
			$compensation = $assistant->getCompensation();
			
			/* Echo each column value */
			echo $universityID ."\t". $firstName . "\t" . $lastName . "\t" . $email . "\t" . $crn . "\t" . $type . "\t" . $classYear . "\t" . $compensation ."\n";

		}
		header("Content-disposition: attachment; filename=".$fileName);	
		
	}
	
?>