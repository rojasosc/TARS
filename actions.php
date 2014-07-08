<?php

// TODO: error handle x15
// TODO: session check x15

	/* Check if an action has been specified, otherwise
	exit. */
	if(isset($_POST['action'])){
		require_once 'db.php';
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
			case 'fetchQualifications':
				fetchQualifications();
				break;
			case 'fetchComments':
				fetchComments();
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
			case 'uploadTerm':
				uploadTerm();
				break;
		}
	}else{
		/*TODO: Add error handling */
		echo "action not specified";
	}
	
	function createProfessor(){
		$office = Place::getPlaceByBuildingAndRoom($_POST['building'],$_POST['room']);	
		$officeID = $office->getPlaceID();
		Professor::registerProfessor($_POST['email'], $_POST['password'], $_POST['firstName'], $_POST['lastName'],
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
		$student->updateProfile($_POST['firstName'],$_POST['lastName'],$_POST['mobilePhone'],$_POST['classYear'],$_POST['major'],$_POST['gpa'],$_POST['universityID'],$_POST['aboutMe']);
		
	}
	
	function updateStudentStatus(){
		Student::setStudentStatus($_POST['userID'],$_POST['status']);
	
	}
	
	function fetchProfessor(){
		if(isset($_POST['userID'])){
			$user = User::getUserByID($_POST['userID']);
			$professor = array();
			if($user){
				$firstName = $user->getFirstName();
				$lastName = $user->getLastName();
				$email = $user->getEmail();
				$mobilePhone = $user->getMobilePhone();
				$officePhone = $user->getOfficePhone();			
				$office = $user->getOffice();

				/* Prepare to encode JSON object */
				$professor = [
				'valid' => true,
				'type' => PROFESSOR,
				'firstName' => $firstName,
				'lastName' => $lastName,
				'email' => $email,
				'mobilePhone' => $mobilePhone,
				'officePhone' => $officePhone,
					'office' => [
						'building' => $office->getBuilding(),
						'room' => $office->getRoom()
						]
					];
				echo json_encode($professor,true);
			}else{
				$error = "User with ID: ".$userID." was not found.";
				echo json_encode(array('valid' => false, 'error' => $error));
			}
		}else{
			$error = "userID POST variable is not set.";
			echo json_encode(array('valid' => false, 'error' => $error));
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
		if(isset($_POST['userID'])){
			$userID = $_POST['userID'];
			$user = User::getUserByID($userID);
			if($user){
				$firstName = $user->getFirstName();
				$lastName = $user->getLastName();
				$email = $user->getEmail();
				$mobilePhone = $user->getMobilePhone();
				$classYear = $user->getClassYear();
				$major = $user->getMajor();
				$gpa = $user->getGPA();
				$universityID = $user->getUniversityID();
				$aboutMe = $user->getAboutMe();
				/* Prepare to encode JSON object */
				$student = array('valid' => true, 'type' => STUDENT,'firstName' => $firstName,'lastName' => $lastName,'email' => $email,'mobilePhone' => $mobilePhone,
				'classYear' => $classYear,'major' => $major,'gpa' => $gpa, 'universityID' => $universityID,'aboutMe' => $aboutMe);
				echo json_encode($student,true);
			}else{
				$error = "User with ID: ".$userID." was not found.";
				echo json_encode(array('valid' => false, 'error' => $error));

			}
		}else{
			$error = "userID POST variable is not set.";
			echo json_encode(array('valid' => false, 'error' => $error));
			
		}
	}

	function fetchQualifications(){
		if(isset($_POST['appID'])){
			$app = Application::getApplicationByID($_POST['appID']);
			$qualifications = $app->getQualifications();
			echo json_encode(array('valid' => true, 'qualifications' => $qualifications),true);
		}else{
			echo json_encode(array('valid' => false),true);
		}
	}

	function fetchComments(){
		$userID = $_POST['userID'];
		$comments = Comment::getAllComments($userID);
			if($comments){
				$studentComments['size'] = count($comments);
				foreach($comments as $comment){
					$author = User::getUserByID($comment->getCreator()->getID());
					$createTime = $comment->getCreateTime();
					$commentText = $comment->getComment();
					$commentHash = [
						"author" => $author->getFirstName() ." ". $author->getLastName(),
						"createTime" => $createTime,
						"comment" => $commentText

					];
					$studentComments[] = $commentHash;					
				}

			}else{
				$studentComments['size'] = 0;

			}		
		echo json_encode($studentComments,true);
	}

	function newStudentComment(){
		$student = User::getUserByID($_POST['studentID'], STUDENT);
		$student->saveComment($_POST['comment'], User::getUserByID($_POST['commenterID'], STAFF), time());
	}
	
	function fetchCourses(){
		$courses = Course::getAllCourses();
		echo json_encode($courses,true);	
		
	}
	
	function fetchBuildings(){
		$buildings = Place::getAllBuildings();
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

	function uploadTerm(){
		$fileName = $_POST['fileName'];
		Term::getTermFromFile($fileName);
	}
	
?>
