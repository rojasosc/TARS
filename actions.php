<?php
// This is the remote call (AJAX) handler
// it only accepts parameters via POST request for now,
// but should now handle errors properly
//
// Required parameters:
// action: The current action name. If it matches one in the $action_map,
// then that action will be attempted and may require additional parameters.
//
// Output format: Always JSON with the following properties:
// "success" (always present): true or false
// "error" (present iff "success" is false): the TarsException object serialized
//    Always has "title" and "message" to be shown in a Bootstrap alert.
// "object" (present depending on action): The returned object
// "objects" (present depending on action): The returned array of objects
// "valid" (present depending on action): The returned boolean (for Bootstrap Validator)
require_once 'session.php';
require_once 'db.php';
require_once 'error.php';
require_once 'formInput.php';

/**
 * A helper function to get the $_POST parameters, or return the error message string
 * on failure.
 *
 * This function may be a little nuts, but it saves me 6 lines per action function
 */
function params($param_names, $param_properties = array(), $isForm = false) {
	$params = get_form_values($param_names);
	$invalids = get_invalid_values($params, $param_properties);
	if (count($invalids) > 0) {
		$s = (count($invalids) == 1) ? '' : 's';
		$i = implode(', ', $invalids);
		if ($is_form) {
			return "Invalid input in field$s. Please fix these fields and try again ($i)";
		} else {
			return "Invalid parameter$s ($i)";
		}
	} else {
		return $params;
	}
}

$output = array();
// check that action was in the POST
if (isset($_REQUEST['action'])) {
	// $action_map: this is a map of action values to action definition structures
	// 'fn' (REQUIRED) is the function to call to execute that action:
	//    It takes one argument $user, which is currently logged in user
	//    and may use $_POST for additional data to return a result value.
	//
	//    Throws: PDOException when the underlying database call fails
	//
	//    The return value is outputted in the format above by following these rules:
	//        The object always contains "success" and "error" (iff success=false)
	//        If the value is NULL, the object contains no other properties.
	//            EXAMPLE USE CASES: signup, apply
	//        If the value is a boolean, the object contains "valid": bool value
	//            EXAMPLE USE CASES: emailAvailable
	//        If the value is an object, the object contains "object": object->toArray()
	//            The object MUST have a toArray() function. 
	//            EXAMPLE USE CASES: fetchStudent, fetchProfessor
	//        If the value is an array, the object contains "objects": array
	//            Assumes the array only contains objects
	//            The objects MUST have a toArray() function.
	//            EXAMPLE USE CASES: searchApplication, searchPosition
	//        If the value is a string, an ERROR_FORM_FIELD is produced with the given string
	//            USE CASE: the given positionID doesn't exist or was not provided
	//
	// 'event' (REQUIRED) is the Event constant that will be used on failure
	// 'noSession' => true allows the action to be performed when not logged in
	// 'userType' specifies the user type to pass to Session::start() (i.e. what user
	//    type the currently logged in user MUST be), don't specify this to accept any
	//    logged in user
	//    (WARNING: if any logged in user is accepted, 'fn' will receive the logged in
	//    user's user object, so make sure 'fn' code doesn't request STUDENT-specific
	//    functions, for example; also 'fn' will receive null if noSession is true and
	//    nobody is logged in)
	// '
	$action_map = array(
		'emailAvailable' => array('event' => Event::USER_CHECK_EMAIL, 'noSession' => true,
			'fn' => function ($user) {
				$params = params(array('email'));
				if (is_array($params)) {
					$email = $params['email'];
					return User::checkEmailAvailable($email);
				} else {
					return $params;
				}
			}),
		'signup' => array('event' => Event::USER_CREATE, 'noSession' => true,
			'fn' => function ($user) {
				$signup_param_props = array(
					'email' => array('type' => FORM_VALIDATE_EMAIL),
					'emailConfirm' => array('type' => FORM_VALIDATE_OTHERFIELD,
						'field' => 'email'),
					'password' => array('type' => FORM_VALIDATE_NOTEMPTY),
					'passwordConfirm' => array('type' => FORM_VALIDATE_OTHERFIELD,
						'field' => 'password'),
					'firstName' => array('type' => FORM_VALIDATE_NOTEMPTY),
					'lastName' => array('type' => FORM_VALIDATE_NOTEMPTY),
					'mobilePhone' => array('type' => FORM_VALIDATE_NUMSTR,
						'min_length' => 10, 'max_length' => 10),
					'classYear' => array('type' => FORM_VALIDATE_NUMSTR,
						'min_length' => 4, 'max_length' => 4),
					'major' => array('type' => FORM_VALIDATE_NOTEMPTY),
					'gpa' => array('type' => FORM_VALIDATE_NUMERIC,
						'min_range' => 0, 'max_range' => 4),
					'universityID' => array('type' => FORM_VALIDATE_NUMSTR,
						'min_length' => 8, 'max_length' => 8),
					'aboutMe' => array('type' => FORM_VALIDATE_NOTEMPTY));
				$signup_params = array_keys($signup_param_props);
				$params = params($signup_params, $signup_param_props, true);
				if (is_array($params)) {
					$studentID = Student::registerStudent(
						$params['email'], $params['password'],
						$params['firstName'], $params['lastName'],
						$params['mobilePhone'], $params['classYear'],
						$params['major'], $params['gpa'],
						$params['universityID'], $params['aboutMe']);
				} else {
					return $params;
				}
			}),
		// TODO convert search.php search form
		'search' => array('event' => Event::STUDENT_SEARCH, 'userType' => STUDENT,
			'fn' => function ($student) {return null;}),
		'apply' => array('event' => Event::STUDENT_APPLY, 'userType' => STUDENT,
			'fn' => function ($student) {
				$params = params(array('positionID','compensation','qualifications'));
				if (is_array($params)) {
					$positionID = $params['positionID'];
					$comp = $params['compensation'];
					$qual = $params['qualifications'];
					$position = Position::getPositionByID($positionID);
					if ($position == null) {
						return 'Position not found';
					}
					if ($position->hasStudentApplied($student)) {
						return 'You have already applied for this position';
					}
					$student->apply($position, $comp, $qual);
					return null;
				} else {
					return $params;
				}
			}),
		'withdraw' => array('event' => Event::STUDENT_WITHDRAW, 'userType' => STUDENT,
			'fn' => function ($student) {
				$params = params(array('positionID'));
				if (is_array($params)) {
					$positionID = $params['positionID'];
					$position = Position::getPositionByID($positionID);
					if ($position == null) {
						return 'Position not found';
					}
					if (!$position->hasStudentApplied($student)) {
						return 'You have not applied for that position';
					}
					$student->withdraw($position);
				} else {
					return $params;
				}
			}),
		'updateProfile' => array('event' => Event::USER_SET_PROFILE, 'userType' => -1,
			'fn' => function ($user) {
				switch ($user->getObjectType()) {
				case STUDENT:
					$profile_param_props = array(
						'firstName' => array('type' => FORM_VALIDATE_NOTEMPTY),
						'lastName' => array('type' => FORM_VALIDATE_NOTEMPTY),
						'mobilePhone' => array('type' => FORM_VALIDATE_NUMSTR,
							'min_length' => 10, 'max_length' => 10),
						'classYear' => array('type' => FORM_VALIDATE_NUMSTR,
							'min_length' => 4, 'max_length' => 4),
						'major' => array('type' => FORM_VALIDATE_NOTEMPTY),
						'gpa' => array('type' => FORM_VALIDATE_NUMERIC,
							'min_range' => 0, 'max_range' => 4),
						'universityID' => array('type' => FORM_VALIDATE_NUMSTR,
							'min_length' => 8, 'max_length' => 8),
						'aboutMe' => array('type' => FORM_VALIDATE_NOTEMPTY));
					$profile_params = array_keys($profile_param_props);
					$params = params($profile_params, $profile_param_props, true);
					if (is_array($params)) {
						$user->updateProfile(
							$params['firstName'], $params['lastName'],
							$params['mobilePhone'], $params['classYear'],
							$params['major'], $params['gpa'],
							$params['universityID'], $params['aboutMe']);
						return null;
					} else {
						return $params;
					}
				}
			}));

	// start of action-handling code
	// First, get the action name
	$action = $_REQUEST['action'];
	// Check if action is known
	if (isset($action_map[$action])) {
		// get the definition structure for this action from the above structure
		$action_def = $action_map[$action];
		// get the function (REQUIRED)
		$action_fn = $action_def['fn'];
		// get the event (REQUIRED)
		$action_event = $action_def['event'];
		// get the session usertype
		$user_type = isset($action_def['userType']) ? $action_def['userType'] : -1;
		// get the noSession option
		$no_session = isset($action_def['noSession']) ? $action_def['noSession'] : false;
		$user = null;
		$error = null;
		if (!$no_session) {
			// if a session is required, start it
			$error = null;
			try {
				$user = Session::start($user_type);
			} catch (TarsException $ex) {
				$error = $ex;
			}
		}
		if ($error == null) {
			// if nothing went wrong (session), run the function and get results
			try {
				// run this action
				$result_obj = $action_fn($user);
				if ($result_obj !== null) {
					if (is_bool($result_obj)) {
						// boolean result
						$output['valid'] = $result_obj;
					} elseif (is_object($result_obj)) {
						// object result
						$output['object'] = $result_obj->toArray();
					} elseif (is_array($result_obj)) {
						// object-array result
						$output['objects'] =
							array_map(function ($obj) {
								return $obj->toArray();
							}, $result_obj);
					} elseif (is_string($result_obj)) {
						// string result is a special one for custom errors from actions
						// they are of the type ERROR_FORM_FIELD
						// ERROR_FORM_FIELD is not a bug in the server
						// Instead, they are the user doing something that
						// they shouldn't or can't,
						// for example applying to a position they already applied to 
						$error = new TarsException(Event::ERROR_FORM_FIELD,
							$action_event, $result_obj);
					}
				}
			} catch (PDOException $ex) {
				// database error
				$error = new TarsException(Event::SERVER_DBERROR, $action_event, $ex);
			}
		}
	} else {
		// unknown action
		$error = new TarsException(Event::ERROR_FORM_FIELD,
			Event::ERROR_FORM_FIELD, 'Unknown action');
	}
} else {
	// missing param
	$error = new TarsException(Event::ERROR_FORM_FIELD,
		Event::ERROR_FORM_FIELD, 'Invalid parameter (action)');
}

// set the success and error properties here
if ($error != null) {
	$output['success'] = false;
	$output['error'] = $error->toArray();
} else {
	$output['success'] = true;
}

// output as JSON
echo json_encode($output);

exit;




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
			case 'setAppStatus':
				setAppStatus();
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
			//TODO: Default case?
		}
	}else{
		/*TODO: Add error handling */
		echo "action not specified";
	}
	
	function setAppStatus(){
		$appID = $_POST['appID'];
		$decision = $_POST['decision'];
		if($decision > 0){
			$app = Application::getApplicationByID($appID);
			Application::setApplicationStatus($app, $decision);
		}
	}

	function createProfessor(){
		$office = Place::getPlaceByBuildingAndRoom($_POST['building'],$_POST['room']);	
		$officeID = $office->getPlaceID();
		Professor::registerProfessor($_POST['email'], $_POST['password'], $_POST['firstName'], $_POST['lastName'],
			$officeID, $_POST['officePhone']);
			
	}
	
	function updateProfessorProfile(){
		$professor = User::getUserByEmail($_POST['email'],PROFESSOR);
		$building = $_POST['building'];
		$room = $_POST['room'];
		$office = Place::getPlaceByBuildingAndRoom($building, $room);
		$officeID = $office->getPlaceID();
		$professor->updateProfile($_POST['firstName'],$_POST['lastName'],$officeID,$_POST['officePhone']);
		
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
				$officePhone = $user->getOfficePhone();			
				$office = $user->getOffice();

				/* Prepare to encode JSON object */
				$professor = [
				'valid' => true,
				'type' => PROFESSOR,
				'firstName' => $firstName,
				'lastName' => $lastName,
				'email' => $email,
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
			$student = $assistant->getCreator();
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
