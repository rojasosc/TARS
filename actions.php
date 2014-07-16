<?php
// This is the remote call (Action) handler
// it accepts parameters via POST request
// but should now handle errors properly
//
// Required parameters:
// action: The current action name. If it matches one in the $action_map,
// then that action will be attempted and may require additional parameters.
// This parameter can be provided by $_GET also (to support Bootstrap Validator)
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

/**
 * A function to tell us whether the given array is "associative" (has any string keys)
 * Source: http://stackoverflow.com/a/4254008/835995
 */
function is_assoc(array $array) {
	return (bool)count(array_filter(array_keys($array), 'is_string'));
}

final class ActionError extends Exception {
	public function __construct($msg) {
		parent::__construct($msg);
	}

	private $msg;
}

/**
 * Actions can be called remotely via requesting this script
 *
 * Error checking is provided by callAction($actionName, $params, $user)
 * It is NOT provided by the action functions themselves
 */
final class Action {
	// To create an action, add its function to this class, then add a corresponding
	// element to $action_map to describe how Action::callAction() has to verify parameters, error check, and event log it.
	//
	// Documentation for these functions is provided with the corresponding $action_map element
	// at the bottom of this class. It's sort of like an interface (i.e. Java)
	public static function login($params, $user, &$eventObject) {
		$sessionUser = Session::login($params['email'], $params['password']);
		$eventObject = $sessionUser->getID();
		return $sessionUser;
	}

	public static function logout($params, $user, &$eventObject) {
		$delayThrow = null;
		$sessionUser = null;
		if (session_start()) {
			try {
				$sessionUser = Session::getLoggedInUser();
			} catch (PDOException $ex) {
				$delayThrow = $ex;
			}
		}

		Session::destroy();

		if ($sessionUser != null) {
			$eventObject = $sessionUser->getID();
		}

		if ($delayThrow != null) {
			throw $delayThrow;
		}
		return null;
	}

	public static function emailAvailable($params, $user, &$eventObject) {
		$email = $params['email'];
		return User::checkEmailAvailable($email);
	}

	public static function signup($params, $user, &$eventObject) {
		$studentID = Student::registerStudent(
			$params['email'], $params['password'],
			$params['firstName'], $params['lastName'],
			$params['mobilePhone'], $params['classYear'],
			$params['major'], $params['gpa'],
			$params['universityID'], $params['aboutMe']);
		$eventObject = $studentID;
		return null;
		// TODO email (probably in ::registerStudent)
	}

	public static function apply($params, $student, &$eventObject) {
		$positionID = $params['positionID'];
		$comp = $params['compensation'];
		$qual = $params['qualifications'];
		$position = Position::getPositionByID($positionID);
		if ($position == null) {
			throw new ActionError('Position not found');
		}
		if ($position->hasStudentApplied($student)) {
			throw new ActionError('You have already applied for this position');
		}
		$appID = $student->apply($position, $comp, $qual);
		$eventObject = $appID;
		return null;
	}

	public static function withdraw($params, $student, &$eventObject) {
		$positionID = $params['positionID'];
		$position = Position::getPositionByID($positionID);
		if ($position == null) {
			throw new ActionError('Position not found');
		}
		if (!$position->hasStudentApplied($student)) {
			throw new ActionError('You have not applied for that position');
		}
		// TODO Application object -> $eventObject
		$student->withdraw($position);
		return null;
	}

	public static function updateProfile($params, $user, &$eventObject) {
		switch ($user->getObjectType()) {
		case STUDENT:
			$user->updateProfile(
				$params['firstName'], $params['lastName'],
				$params['mobilePhone'], $params['classYear'],
				$params['major'], $params['gpa'],
				$params['universityID'], $params['aboutMe']);
			break;
		case PROFESSOR:
			$user->updateProfile(
				$params['firstName'], $params['lastName'],
				$params['officePhone'], $params['building'],
				$params['room']);
			break;
		}
		$eventObject = $user->getID();
		return null;
	}

	public static function changeUserPassword($params, $user, &$eventObject) {
		$oldPassword = $params['oldPassword'];
		$newPassword = $params['newPassword'];
		$confirmPassword = $params['confirmPassword'];
		if (!password_verify($oldPassword, $user->getPassword())) {
			throw new ActionError('Incorrect password');
		}
		$user->changePassword($newPassword);
		$eventObject = $user->getID();
		return null;
	}

	public static function fetchBuildings($params, $user, &$eventObject) {
		return Place::getAllBuildings();
	}

	public static function fetchTheRoom($params, $user, &$eventObject) {
		$places = Place::getPlacesByBuilding($params['building']);
		return array_map(function ($place) {
			return $place->getRoom();
		}, $places);
	}

	public static function fetchUser($params, $user, &$eventObject) {
		$user = User::getUserByID($params['userID'], $params['userType']);
		if ($user == null) {
			throw new ActionError('User not found');
		}
		return $user;
	}

	public static function fetchApplication($params, $user, &$eventObject) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$application = Application::getApplicationByID($params['appID']);
		if ($application == null) {
			throw new ActionError('Application not found');
		}
		return $application;
	}

	public static function fetchComments($params, $user, &$eventObject) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$student = User::getUserByID($params['userID'], STUDENT);
		if ($student == null) {
			throw new ActionError('Student not found');
		}
		$comments = $student->getAllComments();
		return $comments;
	}

	public static function setAppStatus($params, $user, &$eventObject) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$decision = $params['decision'];
		$application = Application::getApplicationByID($params['appID']);
		if ($application == null) {
			throw new ActionError('Application not found');
		}
		if ($user->getObjectType() == PROFESSOR) {
			$position = $application->getPosition();
			$section = $position->getSection();
			if (!$section->isTaughtBy($user)) {
				throw new ActionError('Permission denied (not owner)');
			}
		}
		$application->setApplicationStatus($params['decision']);
		$eventObject = $application->getID();
		return null;
	}

	public static function newStudentComment($params, $user, &$eventObject) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$student = User::getUserByID($params['studentID'], STUDENT);
		if ($student == null) {
			throw new ActionError('Student not found');
		}
		$commentID = $student->saveComment($params['comment'], $user, time());
		$eventObject = $commentID;
		return null;
	}

	public static function searchForUsers($params, $user, &$eventObject) {
		if (is_array($params)) {
			$usersFound = User::findUsers($params['email'],$params['firstName'],
				$params['lastName'], -1);
			return $usersFound;
		}
		return $params;
	}

	// only available via running this script; not by Action::callAction
	public static function uploadTerm($params, $user, &$eventObject) {
		define('CUSTOM_UPLOAD_ERR_CANT_READ', 1001);
		$upload_error_message = function ($code) {
			switch ($code) {
			case UPLOAD_ERR_OK: return 'success';
			case UPLOAD_ERR_INI_SIZE: return 'file size exceeds server limit';
			case UPLOAD_ERR_FORM_SIZE: return 'file size exceeds soft limit';
			case UPLOAD_ERR_PARTIAL: return 'received incomplete file';
			case UPLOAD_ERR_NO_FILE: return 'did not receive file';
			case UPLOAD_ERR_NO_TMP_DIR: return 'missing temporary directory';
			case UPLOAD_ERR_CANT_WRITE: return 'cannot write temporary file';
			case UPLOAD_ERR_EXTENSION: return 'extension stopped upload';
			case CUSTOM_UPLOAD_ERR_CANT_READ: return 'cannot read temporary file';
			default: return 'unknown error';
			}
		};

		$upload = $_FILES['termFile'];
		switch ($upload['error']) {
		case UPLOAD_ERR_OK:
			$lines = @file($upload['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			if ($lines) {
				$termID = Term::importTermFromCSV($params['termYear'], $params['termSemester'], $lines, $upload);
				break;
			} else {
				$upload['error'] = CUSTOM_UPLOAD_ERR_CANT_READ;
				// intentional fall-through to DEFAULT
			}
		default:
			throw new TarsException(Event::ERROR_FORM_UPLOAD,
				Event::STAFF_TERM_IMPORT,
				array($upload_error_message($upload['error'])));
		}
		$eventObject = $termID;
		return null;
	}

	const VALIDATE_NOTEMPTY = 1;
	const VALIDATE_EMAIL = 2;
	const VALIDATE_OTHERFIELD = 3;
	const VALIDATE_NUMERIC = 4;
	const VALIDATE_NUMSTR = 5;
	const VALIDATE_UPLOAD = 11;

	/**
	 * This function is not an Action.
	 * Validates the parameters in $input according to the $definitions array.
	 *
	 * $input: The input, such as from $_POST, or constructed to pass to callAction.
	 *
	 * $definitions: This is 'params' in the $action_map
	 */
	private static function validateParameters($input, $definitions) {
		if (!is_assoc($definitions)) {
			// if params was just a list of keys,
			// assign them all default settings
			$definitions = array_fill_keys($definitions,
				array('type' => Action::VALIDATE_NOTEMPTY));
		}
		$invalids = array();
		foreach ($definitions as $param_key => $def) {
			if (array_key_exists($param_key, $input) && ($input[$param_key] !== '')) {
				$value = $input[$param_key];
				if (is_int($def)) {
					$def = array('type' => $def);
				} elseif (!isset($def['type'])) {
					$def['type'] = Action::VALIDATE_NOTEMPTY;
				}

				$invalid = false;
				switch ($def['type']) {
				case Action::VALIDATE_NOTEMPTY:
					$invalid = ($value === '');
					break;
				case Action::VALIDATE_EMAIL:
					$invalid = filter_var($value, FILTER_VALIDATE_EMAIL) === false;
					break;
				case Action::VALIDATE_OTHERFIELD:
					if (isset($def['field'])) {
						$other_key = $def['field'];
						if (isset($input[$other_key])) {
							$invalid = ($input[$other_key] !== $value);
							break;
						}
					}
					$invalid = true;
					break;
				case Action::VALIDATE_NUMERIC:
					if (isset($def['reject_signed']) && ($value[0] == '-' || $value[0] == '+')) {
						$value = '';
					}
					if (isset($def['reject_decimal']) && strpos($value, '.') !== false) {
						$value = '';
					}
					$invalid = filter_var($value, FILTER_VALIDATE_FLOAT, $def) === false;
					break;
				case Action::VALIDATE_NUMSTR:
					$value = preg_replace('/([^\d]+)/', '', $value);
					if (isset($def['min_length']) && strlen($value) < $def['min_length']) {
						$value = '';
					}
					if (isset($def['max_length']) && strlen($value) > $def['max_length']) {
						$value = '';
					}
					$invalid = ($value === '');
					break;
				}
				if ($invalid) {
					$invalids[] = $param_key;
				}
			} elseif ($def ==  Action::VALIDATE_UPLOAD ||
					(isset($def['type']) && $def['type'] == Action::VALIDATE_UPLOAD)) {
				// this right here is why forms with VALIDATE_UPLOAD field
				// cannot be called by Action::callAction(): it accesses $_FILES
				if (!isset($_FILES[$param_key])) {
					$invalids[] = $param_key;
				}
			} elseif (!isset($def['optional']) || !$def['optional']) {
				$invalids[] = $param_key;
			}
		}
		return $invalids;
	}

	// $action_map: this is a map of action keys to action definition structures
	// The keys of this array must have corresponding class functions defined with the same name:
	//    It takes three arguments:
	//        $params: The $_POST array
	//        $user: which is currently logged in (null if nobody is).
	//        &$eventObject: this can be set by the function to an object ID to put in the event log for this Action
	//
	//    Throws: PDOException when the underlying database call fails
	//            ActionError if a custom error is generated, is passed to create a TarsException in callAction
	//            TarsException if a structured error is generated
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
	// 'event' => (REQUIRED) is the Event constant that will be used on failure
	// 'params' => is an array of the parameters.
	//    If one is not provided correctly, an ERROR_FORM_FIELD is thrown
	//    Format:
	//        1. array of $key => $definition pairs, giving rules for the $definition
	//            The $definition array has the following elements:
	//            'type' => the Action::VALIDATE_* constant that describes what the $value MUST be
	//            VALIDATE_NOTEMPTY: Just checks that the $value is not empty-string
	//            VALIDATE_EMAIL: Checks that the $value is a valid email address
	//            VALIDATE_OTHERFIELD: Checks that the $value is equal to the $value of $input with key $def['field']
	//            VALIDATE_NUMERIC: Checks that the $value is a number.
	//                It must be >= $def['min_range'] (optional)
	//                It must be <= $def['max_range'] (optional)
	//                It cannot start with a '+' or '-' if $def['reject_signed'] is set
	//                It cannot contain a '.' if $def['reject_decimal'] is set
	//            VALIDATE_NUMSTR: Checks that the $value is a string of digits (phone, uni ID)
	//                It must be shorter or equal to $def['min_length'] (optional)
	//                It must be longer or equal to $def['max_length'] (optional)
	//                It will validate while containing non-digits if $def['accept_nondigits'] is set (they will be removed before length checks)
	//            VALIDATE_UPLOAD: Checks that the $value is a file.
	//                This special validation checks $_FILES regardless of the contents of $input,
	//                making it not work when you call Action::callAction() unless done from an upload
	//            'optional' => if set, then either $value is not empty and validates using the above rules, or it is empty or not present in $input. If it is not empty and invalid, validation will fail.
	//        2. $definition may also be the 'type' constant alone, in which case it'll be interpreted as array('type' => $validate_constant).
	//        3. The entire array can just be an array of strings, representing all $input[$key]s as 'type' VALIDATE_NOTEMPTY with no options. The array CANNOT be associative in this case (string keys).
	// 'eventLog' => is either 'debug' (DEFAULT): the Event will not be logged on success, or 'always': the event will be logged on success
	// 'eventDescr' => The description to pass to the event log. One "%s" is allowed to represent the user who did the action.
	// 'eventDescrArg' => The location to get the user who did the action argument:
	//    'session' (DEFAULT): Uses the user who is logged in
	//    'refparam': Uses the value of $eventObject as the user, if it is a User object
	// 'isUserInput' => is a boolean. TRUE returns the 'Invalid input in fields' error;
	//    FALSE returns the 'Invalid parameter' error.
	//    USAGE: TRUE for signup, search (user input);
	//    FALSE for apply, emailAvailable (automated input/result of button-like request)
	// 'noSession' => is a boolean. TRUE allows the action to be performed when not logged in
	// 'userType' => specifies the user type to pass to Session::start() (i.e. what user
	//    type the currently logged in user MUST be), don't specify this to accept any
	//    logged in user
	//    (WARNING: if any logged in user is accepted, 'fn' will receive the logged in
	//    user's user object, so make sure 'fn' code doesn't request STUDENT-specific
	//    functions, for example; also 'fn' will receive null if noSession is true and
	//    nobody is logged in)
	private static $action_map = array(
		// Action:           login 
		// Session required: none
		// Parameters:
		//     email: email field value
		//     password: password field value
		// Returns:
		//     object: Your user object on successful login
		//     success and error: Action status
		'login' => array('event' => Event::SESSION_LOGIN,
			'eventLog' => 'always', 'eventDescr' => '%s logged in.',
			'eventDescrArg' => 'refparam', 'isUserInput' => true, 'noSession' => true,
			'params' => array('email', 'password')),
		// Action:           logout
		// Session required: none (handled by code)
		// Parameters:       none
		// Returns:
		//     success and error: Action status
		// Note:             This Action does not log failure due to no session
		'logout' => array('event' => Event::SESSION_LOGOUT, 'noSession' => true,
			'eventLog' => 'always', 'eventDescr' => '%s logged out.',
			'eventDescrArg' => 'refparam'),
		// Action:           emailAvailable 
		// Session required: none
		// Parameters:
		//     email: the email to check
		// Returns:
		//     valid: Whether the email can be used
		//     success and error: Action status
		'emailAvailable' => array('event' => Event::USER_IS_EMAIL_AVAIL,
			'eventDescr' => 'A user checked for the availability of an email address.',
			'noSession' => true, 'params' => array('email')),
		// Action:           signup 
		// Session required: none
		// Parameters:       all user fields
		//     email, emailConfirm, password, passwordConfirm,
		//     firstName, lastName, mobilePhone, classYear, major,
		//     gpa, universityID, aboutMe
		// Returns:
		//     success and error: Action status
		'signup' => array('event' => Event::USER_CREATE, 'noSession' => true,
			'eventLog' => 'always', 'eventDescr' => '%s created a STUDENT account.',
			'eventDescrArg' => 'refparam',
			'isUserInput' => true, 'params' => array(
					'email' => array('type' => Action::VALIDATE_EMAIL),
					'emailConfirm' => array('type' => Action::VALIDATE_OTHERFIELD,
						'field' => 'email'),
					'password' => array('type' => Action::VALIDATE_NOTEMPTY),
					'passwordConfirm' => array('type' => Action::VALIDATE_OTHERFIELD,
						'field' => 'password'),
					'firstName' => array('type' => Action::VALIDATE_NOTEMPTY),
					'lastName' => array('type' => Action::VALIDATE_NOTEMPTY),
					'mobilePhone' => array('type' => Action::VALIDATE_NUMSTR,
						'min_length' => 10, 'max_length' => 10),
					'classYear' => array('type' => Action::VALIDATE_NUMSTR,
						'min_length' => 4, 'max_length' => 4),
					'major' => array('type' => Action::VALIDATE_NOTEMPTY),
					'gpa' => array('type' => Action::VALIDATE_NUMERIC,
						'min_range' => 0, 'max_range' => 4),
					'universityID' => array('type' => Action::VALIDATE_NUMSTR,
						'min_length' => 8, 'max_length' => 8),
					'aboutMe' => array('type' => Action::VALIDATE_NOTEMPTY))),
		// Action:           search 
		// Session required: STUDENT
		// Parameters:
		// Returns:
		//     success and error: Action status
		// TODO convert search.php search form
		// TODO paginate here
		'search' => array('event' => Event::USER_GET_VIEW, 'userType' => STUDENT),
		// Action:           apply
		// Session required: STUDENT
		// Parameters:
		//     positionID:   Position ID
		//     compensation: pay|credit
		//     qualifications: text field
		// Returns:
		//     success and error: Action status
		'apply' => array('event' => Event::STUDENT_APPLY, 'userType' => STUDENT,
			'eventLog' => 'always', 'eventDescr' => '%s created an application.',
			'isUserInput' => true, 'params' => array('positionID','compensation','qualifications')),
		// Action:           withdraw
		// Session required: STUDENT
		// Parameters:
		//     positionID:   Position ID
		//     TODO support withdraw type and reason
		//     TODO make withdraw parameter the Application ID
		// Returns:
		//     success and error: Action status
		'withdraw' => array('event' => Event::STUDENT_WITHDRAW, 'userType' => STUDENT,
			'eventLog' => 'always', 'eventDescr' => '%s withdrew an application.',
			'isUserInput' => true, 'params' => array('positionID')),
		// Action:           updateProfile
		// Session required: STUDENT, PROFESSOR, STAFF
		// Parameters:       all user fields (varies with session user type)
		//     firstName, lastName, mobilePhone, classYear, major,
		//     gpa, universityID, aboutMe, officePhone, officeBuilding, officeRoom
		// Returns:
		//     success and error: Action status
		'updateProfile' => array('event' => Event::USER_SET_PROFILE,
			'eventLog' => 'always', 'eventDescr' => '%s updated their profile.',
			'isUserInput' => true, 'params' => array(
				'firstName' => array('type' => Action::VALIDATE_NOTEMPTY),
				'lastName' => array('type' => Action::VALIDATE_NOTEMPTY),
				'mobilePhone' => array('type' => Action::VALIDATE_NUMSTR,
					'optional' => true, 'min_length' => 10, 'max_length' => 10),
				'classYear' => array('type' => Action::VALIDATE_NUMSTR,
					'optional' => true, 'min_length' => 4, 'max_length' => 4),
				'major' => array('type' => Action::VALIDATE_NOTEMPTY,
					'optional' => true),
				'gpa' => array('type' => Action::VALIDATE_NUMERIC,
					'optional' => true, 'min_range' => 0, 'max_range' => 4),
				'universityID' => array('type' => Action::VALIDATE_NUMSTR,
					'optional' => true, 'min_length' => 8, 'max_length' => 8),
				'aboutMe' => array('type' => Action::VALIDATE_NOTEMPTY,
					'optional' => true),
				'officePhone' => array('type' => Action::VALIDATE_NUMSTR,
					'optional' => true, 'min_length' => 10, 'max_length' => 10),
				'building' => array('type' => Action::VALIDATE_NOTEMPTY,
					'optional' => true),
				'room' => array('type' => Action::VALIDATE_NOTEMPTY,
					'optional' => true))),
		// Action:           changeUserPassword
		// Session required: logged in
		// Parameters:
		//     oldPassword, newPassword, confirmPassword
		// Returns:
		//     success and error: Action status
		'changeUserPassword' => array('event' => Event::USER_SET_PASS,
			'eventLog' => 'always', 'eventDescr' => '%s changed their password.',
			'isUserInput' => true, 'params' => array(
					'oldPassword' => array('type' => Action::VALIDATE_NOTEMPTY),
					'newPassword' => array('type' => Action::VALIDATE_NOTEMPTY),
					'confirmPassword' => array('type' => Action::VALIDATE_OTHERFIELD,
						'field' => 'newPassword'))),
		// Action:           fetchBuildings
		// Session required: logged in
		// Parameters:       none
		// Returns:
		//     objects: array of building names
		//     success and error: Action status
		'fetchBuildings' => array('event' => Event::USER_GET_OBJECT,
			'eventDescr' => '%s retrieved buildings list.'),
		// Action:           fetchTheRooms
		// Session required: logged in
		// Parameters:
		//     building: name of building to look in
		// Returns:
		//     objects: array of room numbers (or names) in this building
		//     success and error: Action status
		'fetchTheRoom' => array('event' => Event::USER_GET_OBJECT,
			'eventDescr' => '%s retrieved building room list.',
			'params' => array('building')),
		// Action:           fetchUser
		// Session required: logged in
		// Parameters:
		//     userID: The user's ID
		//     userType: The expected user type (pass -1 for any)
		// Returns:
		//     object: The user's data
		//     success and error: Action status
		'fetchUser' => array('event' => Event::USER_GET_OBJECT,
			'eventDescr' => '%s retrieved user object.',
			'params' => array('userID', 'userType')),
		// Action:           fetchApplication
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     appID: The application ID
		// Returns:
		//     object: The application data
		//     success and error: Action status
		'fetchApplication' => array('event' => Event::USER_GET_OBJECT,
			'eventDescr' => '%s retrieved application object.',
			'params' => array('appID')),
		// Action:           fetchComments
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     userID: The user ID
		// Returns:
		//     objects: The comments
		//     success and error: Action status
		'fetchComments' => array('event' => Event::USER_GET_VIEW,
			'eventDescr' => '%s retrieved comments view.',
			'params' => array('userID')),
		// Action:           setAppStatus
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     appID: The app ID
		//     decision: The new app status
		// Returns:
		//     success and error: Action status
		'setAppStatus' => array('event' => Event::NONSTUDENT_SET_APP,
			'eventLog' => 'always', 'eventDescr' => '%s updated the status of an application.',
			'params' => array('appID', 'decision')),
		// Action:           newStudentComment
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     studentID: referred-to student
		//     comment: comment text
		// Returns:
		//     success and error: Action status
		'newStudentComment' => array('event' => Event::NONSTUDENT_COMMENT,
			'eventLog' => 'always', 'eventDescr' => '%s created a comment.',
			'isUserInput' => true, 'params' => array('studentID', 'comment')),
		// Action:           searchForUsers
		// Session required: STAFF
		// Parameters:
		//     email: email field
		//     firstName: first name field
		//     lastName: last name field
		//     userTypes: STUDENT, PROFESSOR or -1 for either
		// Returns:
		//     objects: The users in this set
		//     success and error: Action status
		// TODO paginate here
		'searchForUsers' => array('event' => Event::USER_GET_VIEW, 'userType' => STAFF,
			'eventDescr' => '%s retrieved positions view.',
			'isUserInput' => true, 'params' => array(
				'email' => array('optional' => true),
				'firstName' => array('optional' => true),
				'lastName' => array('optional' => true))),
		// Action:           uploadTerm
		// Session required: STAFF
		// Parameters:
		//     termYear: year field
		//     termSemester: semester field
		//     termFile: the file data
		// Returns:
		//     success and error: Action status
		// Only available via calling this script; not by Action::callAction()
		'uploadTerm' => array('event' => Event::STAFF_TERM_IMPORT, 'userType' => STAFF,
			'eventLog' => 'always', 'eventDescr' => '%s uploaded a term.',
			'isUserInput' => true, 'params' => array(
				'termYear' => array('type' => Action::VALIDATE_NUMSTR,
					'min_length' => 4, 'max_length' => 4),
				'termSemester' => Action::VALIDATE_NOTEMPTY,
				'termFile' => Action::VALIDATE_UPLOAD)));
	// end Action::$action_map

	public static function callAction($actionName, $input = array(), $jsonOutput = false) {
		// Check if action is known
		if (isset(Action::$action_map[$actionName])) {
			// get the definition structure for this action from the above structure
			$action_def = Action::$action_map[$actionName];
			// get the event (REQUIRED)
			$action_event = $action_def['event'];
			// get the session usertype
			$action_utype = isset($action_def['userType']) ? $action_def['userType'] : -1;
			// get the noSession option
			$action_nosess = isset($action_def['noSession']) ? $action_def['noSession'] : false;
			// get the parameters
			$action_params = isset($action_def['params']) ? $action_def['params'] : array();
			// get whether errors should be "Invalid form field" or "Invalid parameter"
			$action_userinput = isset($action_def['isUserInput']) ? $action_def['isUserInput'] : false;
			$action_evlog = isset($action_def['eventLog']) ? $action_def['eventLog'] : 'debug';
			$action_evdesc = isset($action_def['eventDescr']) ? $action_def['eventDescr'] : '%s did something.';
			$action_evdesc_arg = isset($action_def['eventDescrArg']) ? $action_def['eventDescrArg'] : 'session';
			// SESSION
			$user = null;
			$error = null;
			if (!$action_nosess) {
				// if a session is required, start it
				$error = null;
				try {
					$user = Session::start($action_utype);
				} catch (TarsException $ex) {
					$error = $ex;
				}
			}

			// RUN ACTION
			if ($error == null) {
				// if nothing went wrong (session), run the function and get results
				try {

					// VALIDATE PARAMETERS
					$invalids = Action::validateParameters($input, $action_params);

					if (count($invalids) > 0) {
						$s = (count($invalids) == 1) ? '' : 's';
						$i = implode(', ', $invalids);
						if ($action_userinput) {
							throw new ActionError("Invalid input in field$s. Please fix these fields and try again ($i)");
						} else {
							throw new ActionError("Invalid parameter$s ($i)");
						}
					} else {
						// successful
						$event_object = null;
						$result_obj = Action::$actionName($input, $user, $event_object);
					}

					// GET RESULT
					if ($result_obj !== null) {
						if (is_bool($result_obj)) {
							// boolean result
							$output['valid'] = $result_obj;
						} elseif (is_object($result_obj)) {
							// object result
							$output['object'] = $result_obj->toArray();
						} elseif (is_array($result_obj)) {
							// object-array result
							if (is_assoc($result_obj)) {
								// object result (as array)
								$output['object'] = $result_obj;
							} else {
								// array of objects result
								$output['objects'] =
									array_map(function ($obj) {
										if (is_object($obj)) {
											// as object
											return $obj->toArray();
										} else {
											return $obj;
										}
									}, $result_obj);
							}
						} else {
							// int, float, or string
							$output['value'] = $result_obj;
						}
					}
				} catch (ActionError $ex) {
					// ERROR_FORM_FIELD with replacement message
					$error = new TarsException(Event::ERROR_FORM_FIELD,
						$action_event, $ex->getMessage());
				} catch (PDOException $ex) {
					// SERVER_DBERROR
					$error = new TarsException(Event::SERVER_DBERROR, $action_event, $ex);
				} catch (TarsException $ex) {
					// non-ERROR_FORM_FIELDs thrown by Actions
					$error = $ex;
				}
			}

			// LOG SUCCESS
			if ($error == null && $action_evlog == 'always') {
				$source_user = null;
				switch ($action_evdesc_arg) {
				case 'refparam': $source_user = User::getUserByID($event_object); break;
				case 'session': $source_user = $user; break;
				//case 'return': $descrarg = $result_obj; break;
				}
				if ($source_user != null && $source_user instanceof User) {
					$descrarg = $source_user->getName();
				} else {
					$descrarg = '(unknown user)';
					$source_user = null; // do not give the database non-Users in creator column
				}
				$descr = sprintf($action_evdesc, $descrarg);
				// insert event with this EventID, description, object,
				// current time, current IP (default parameter), and current user object
				Event::insertEvent($action_event, $descr, $event_object, time(), null, $source_user);
			}
		} else {
			// unknown action
			$error = new TarsException(Event::ERROR_FORM_FIELD,
				Event::ERROR_FORM_FIELD, 'Unknown action');
		}

		// set the success and error properties here
		if ($jsonOutput) {
			if ($error != null) {
				$output['success'] = false;
				$output['error'] = $error->toArray();
			} else {
				$output['success'] = true;
			}
			return json_encode($output);
		} else {
			if ($error != null) {
				throw $error;
			} else {
				return $result_obj;
			}
		}
	}
}

if (isset($_SERVER["SCRIPT_FILENAME"]) && basename($_SERVER["SCRIPT_FILENAME"]) == 'actions.php') {
	// check that action was in the request (POST or GET), but always have a value
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

	$output = Action::callAction($action, $_POST, true);

	// output as JSON
	echo $output;
}

