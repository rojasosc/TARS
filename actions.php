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
require_once 'formInput.php';


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
	public static function emailAvailable($params, $user) {
		$email = $params['email'];
		return User::checkEmailAvailable($email);
	}

	public static function signup($params, $user) {
		$studentID = Student::registerStudent(
			$params['email'], $params['password'],
			$params['firstName'], $params['lastName'],
			$params['mobilePhone'], $params['classYear'],
			$params['major'], $params['gpa'],
			$params['universityID'], $params['aboutMe']);
		return null;
		// TODO email (probably in ::registerStudent)
	}

	public static function apply($params, $student) {
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
		$student->apply($position, $comp, $qual);
		return null;
	}

	public static function withdraw($params, $student) {
		$positionID = $params['positionID'];
		$position = Position::getPositionByID($positionID);
		if ($position == null) {
			throw new ActionError('Position not found');
		}
		if (!$position->hasStudentApplied($student)) {
			throw new ActionError('You have not applied for that position');
		}
		$student->withdraw($position);
		return null;
	}

	public static function updateProfile($params, $user) {
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
		return null;
	}

	public static function changeUserPassword($params, $user) {
		$oldPassword = $params['oldPassword'];
		$newPassword = $params['newPassword'];
		$confirmPassword = $params['confirmPassword'];
		if (password_verify($oldPassword, $user->getPassword())) {
			$user->changePassword($newPassword);
			return null;
		} else {
			throw 'Incorrect password';
		}
	}

	public static function fetchBuildings($params, $user) {
		return Place::getAllBuildings();
	}

	public static function fetchTheRoom($params, $user) {
		$places = Place::getPlacesByBuilding($params['building']);
		return array_map(function ($place) {
			return $place->getRoom();
		}, $places);
	}

	public static function fetchUser($params, $user) {
		$user = User::getUserByID($params['userID'], $params['userType']);
		if ($user == null) {
			throw new ActionError('User not found');
		} else {
			return $user;
		}
	}

	public static function fetchApplication($params, $user) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$application = Application::getApplicationByID($params['appID']);
		if ($application == null) {
			throw new ActionError('Application not found');
		} else {
			return $application;
		}
	}

	public static function fetchComments($params, $user) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$student = User::getUserByID($params['userID'], STUDENT);
		if ($student == null) {
			throw new ActionError('Student not found');
		} else {
			$comments = $student->getAllComments();
			return $comments;
		}
	}

	public static function setAppStatus($params, $user) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$decision = $params['decision'];
		$application = Application::getApplicationByID($params['appID']);
		if ($application == null) {
			throw new ActionError('Application not found');
		} else {
			if ($user->getObjectType() == PROFESSOR) {
				if (!$application->getPosition()->getSection()->isTaughtBy($user)) {
					throw new ActionError('Permission denied (not owner)');
				}
			}
			$application->setApplicationStatus($params['decision']);
			return null;
		}
	}

	public static function newStudentComment($params, $user) {
		if ($user->getObjectType() == STUDENT) {
			throw new ActionError('Permission denied (student)');
		}
		$student = User::getUserByID($params['studentID'], STUDENT);
		if ($student == null) {
			throw new ActionError('Student not found');
		} else {
			$student->saveComment($params['comment'], $user, time());
			return null;
		}
	}

	public static function searchForUsers($params, $user) {
		if (is_array($params)) {
			$usersFound = User::findUsers($params['email'],$params['firstName'],
				$params['lastName'], -1);
			return $usersFound;
		} else {
			return $params;
		}
	}

	// only available via running this script; not by Action::callAction
	public static function uploadTerm($params, $user) {
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
				Term::importTermFromCSV($params['termYear'], $params['termSemester'], $lines, $upload);
				break;
			} else {
				$upload['error'] = CUSTOM_UPLOAD_ERR_CANT_READ;
				// intentional fall-through to DEFAULT
			}
		default:
			throw new TarsException(Event::ERROR_FORM_UPLOAD,
				Event::STAFF_TERM_IMPORT,
				$upload_error_message($upload['error']));
		}
		return null;
	}

	const VALIDATE_NOTEMPTY = 1;
	const VALIDATE_EMAIL = 2;
	const VALIDATE_OTHERFIELD = 3;
	const VALIDATE_NUMERIC = 4;
	const VALIDATE_NUMSTR = 5;
	const VALIDATE_UPLOAD = 11;

	// $action_map: this is a map of action keys to action definition structures
	// The keys of this array must have corresponding class functions defined with the same name:
	//    It takes two arguments:
	//        $params: The $_POST array
	//        $user: which is currently logged in (null if nobody is).
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
	//    TODO insert successful events here rather than sprawled around db.php?
	// 'params' is an array of the parameters.
	//    If one is not provided correctly (uses the rules of formInput.php's
	//    get_form_values and get_invalid_values), an error is raised
	// 'isUserInput' is a boolean. TRUE returns the 'Invalid input in fields' error;
	//    FALSE returns the 'Invalid parameter' error.
	//    USAGE: TRUE for signup, search (user input);
	//    FALSE for apply, emailAvailable (automated input/result of button-like request)
	// 'noSession' is a boolean. TRUE allows the action to be performed when not logged in
	// 'userType' specifies the user type to pass to Session::start() (i.e. what user
	//    type the currently logged in user MUST be), don't specify this to accept any
	//    logged in user
	//    (WARNING: if any logged in user is accepted, 'fn' will receive the logged in
	//    user's user object, so make sure 'fn' code doesn't request STUDENT-specific
	//    functions, for example; also 'fn' will receive null if noSession is true and
	//    nobody is logged in)
	private static $action_map = array(
		// Action:           emailAvailable 
		// Session required: none
		// Parameters:
		//     email: the email to check
		// Returns:
		//     valid: Whether the email can be used
		//     success and error: Action status
		'emailAvailable' => array('event' => Event::USER_CHECK_EMAIL,
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
		'search' => array('event' => Event::STUDENT_SEARCH, 'userType' => STUDENT),
		// Action:           apply
		// Session required: STUDENT
		// Parameters:
		//     positionID:   Position ID
		//     compensation: pay|credit
		//     qualifications: text field
		// Returns:
		//     success and error: Action status
		'apply' => array('event' => Event::STUDENT_APPLY, 'userType' => STUDENT,
			'isUserInput' => true, 'params' => array('positionID','compensation','qualifications')),
		// Action:           withdraw
		// Session required: STUDENT
		// Parameters:
		//     positionID:   Position ID
		//     TODO support withdraw type and reason
		// Returns:
		//     success and error: Action status
		'withdraw' => array('event' => Event::STUDENT_WITHDRAW, 'userType' => STUDENT,
			'isUserInput' => true, 'params' => array('positionID')),
		// Action:           updateProfile
		// Session required: STUDENT, PROFESSOR, STAFF
		// Parameters:       all user fields (varies with session user type)
		//     firstName, lastName, mobilePhone, classYear, major,
		//     gpa, universityID, aboutMe, officePhone, officeBuilding, officeRoom
		// Returns:
		//     success and error: Action status
		'updateProfile' => array('event' => Event::USER_SET_PROFILE,
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
		'changeUserPassword' => array('event' => Event::USER_SET_PROFILE,
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
		'fetchBuildings' => array('event' => Event::USER_GET_PROFILE),
		// Action:           fetchTheRooms
		// Session required: logged in
		// Parameters:
		//     building: name of building to look in
		// Returns:
		//     objects: array of room numbers (or names) in this building
		//     success and error: Action status
		'fetchTheRoom' => array('event' => Event::USER_GET_PROFILE,
			'params' => array('building')),
		// Action:           fetchUser
		// Session required: logged in
		// Parameters:
		//     userID: The user's ID
		//     userType: The expected user type (pass -1 for any)
		// Returns:
		//     object: The user's data
		//     success and error: Action status
		'fetchUser' => array('event' => Event::USER_GET_PROFILE,
			'params' => array('userID', 'userType')),
		// Action:           fetchApplication
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     appID: The application ID
		// Returns:
		//     object: The application data
		//     success and error: Action status
		'fetchApplication' => array('event' => Event::USER_GET_PROFILE,
			'params' => array('appID')),
		// Action:           fetchComments
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     userID: The user ID
		// Returns:
		//     objects: The comments
		//     success and error: Action status
		'fetchComments' => array('event' => Event::USER_GET_PROFILE,
			'params' => array('userID')),
		// Action:           setAppStatus
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     appID: The app ID
		//     decision: The new app status
		// Returns:
		//     success and error: Action status
		'setAppStatus' => array('event' => Event::NONSTUDENT_SET_APP,
			'params' => array('appID', 'decision')),
		// Action:           newStudentComment
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     studentID: referred-to student
		//     comment: comment text
		// Returns:
		//     success and error: Action status
		'newStudentComment' => array('event' => Event::NONSTUDENT_COMMENT,
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
		'searchForUsers' => array('event' => Event::USER_GET_PROFILE, 'userType' => STAFF,
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
			'isUserInput' => true, 'params' => array(
				'termYear' => array('type' => Action::VALIDATE_NUMSTR,
					'min_length' => 4, 'max_length' => 4),
				'termSemester' => Action::VALIDATE_NOTEMPTY,
				'termFile' => Action::VALIDATE_UPLOAD)));

	private static function validateParameters($input, $definitions) {
		if (!is_assoc($definitions)) {
			// if params was just a list of keys,
			// assign them all default settings
			$definitions = array_fill_keys($definitions,
				array('type' => Action::VALIDATE_NOTEMPTY));
		}
		$invalids = array();
		foreach ($definitions as $param_key => $def) {
			if (array_key_exists($param_key, $input)) {
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
				case Action::VALIDATE_UPLOAD:
					// this right here is why forms with VALIDATE_UPLOAD field
					// cannot be called by Action::callAction(): it accesses $_FILES
					if (!isset($_FILES[$param_key])) {
						$invalid = true;
					}
					break;
				}
				if ($invalid) {
					$invalids[] = $param_key;
				}
			} elseif (!isset($def['optional']) || !$def['optiona']) {
				$invalids[] = $param_key;
			}
		}
		return $invalids;
	}

	public static function callAction($actionName, $input) {
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
						$result_obj = call_user_func(array('Action', $actionName), $input, $user);
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
		} else {
			// unknown action
			$error = new TarsException(Event::ERROR_FORM_FIELD,
				Event::ERROR_FORM_FIELD, 'Unknown action');
		}

		// set the success and error properties here
		if ($error != null) {
			$output['success'] = false;
			$output['error'] = $error->toArray();
		} else {
			$output['success'] = true;
		}
		return $output;
	}
}

// check that action was in the request (POST or GET), but always have a value
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

$output = Action::callAction($action, $_POST);

// output as JSON
echo json_encode($output);

