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
		if ($isForm) {
			return "Invalid input in field$s. Please fix these fields and try again ($i)";
		} else {
			return "Invalid parameter$s ($i)";
		}
	} else {
		return $params;
	}
}

/**
 * A function to tell us whether the given array is "associative" (has any string keys)
 * Source: http://stackoverflow.com/a/4254008/835995
 */
function is_assoc(array $array) {
	return (bool)count(array_filter(array_keys($array), 'is_string'));
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
		// Action:           emailAvailable 
		// Session required: none
		// Parameters:
		//     email: the email to check
		// Returns:
		//     valid: Whether the email can be used
		//     success and error: Action status
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
		// Action:           signup 
		// Session required: none
		// Parameters:       all user fields
		//     email, emailConfirm, password, passwordConfirm,
		//     firstName, lastName, mobilePhone, classYear, major,
		//     gpa, universityID, aboutMe
		// Returns:
		//     success and error: Action status
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
		// Action:           search 
		// Session required: STUDENT
		// Parameters:
		// Returns:
		//     success and error: Action status
		// TODO convert search.php search form
		'search' => array('event' => Event::STUDENT_SEARCH, 'userType' => STUDENT,
			'fn' => function ($student) {return null;}),
		// Action:           apply
		// Session required: STUDENT
		// Parameters:
		//     positionID:   Position ID
		//     compensation: pay|credit
		//     qualifications: text field
		// Returns:
		//     success and error: Action status
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
		// Action:           withdraw
		// Session required: STUDENT
		// Parameters:
		//     positionID:   Position ID
		// Returns:
		//     success and error: Action status
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
		// Action:           updateProfile
		// Session required: STUDENT, PROFESSOR, STAFF
		// Parameters:       all user fields (varies with session user type)
		//     firstName, lastName, mobilePhone, classYear, major,
		//     gpa, universityID, aboutMe, officePhone, officeBuilding, officeRoom
		// Returns:
		//     success and error: Action status
		'updateProfile' => array('event' => Event::USER_SET_PROFILE,
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
				case PROFESSOR:
					$profile_param_props = array(
						'firstName' => array('type' => FORM_VALIDATE_NOTEMPTY),
						'lastName' => array('type' => FORM_VALIDATE_NOTEMPTY),
						'officePhone' => array('type' => FORM_VALIDATE_NUMSTR,
							'min_length' => 10, 'max_length' => 10),
						'building' => array('type' => FORM_VALIDATE_NOTEMPTY),
						'room' => array('type' => FORM_VALIDATE_NOTEMPTY));
					$profile_params = array_keys($profile_param_props);
					$params = params($profile_params, $profile_param_props, true);
					if (is_array($params)) {
						$user->updateProfile(
							$params['firstName'], $params['lastName'],
							$params['officePhone'], $params['building'],
							$params['room']);
						return null;
					} else {
						return $params;
					}
				}
			}),
		// Action:           changeUserPassword
		// Session required: logged in
		// Parameters:
		//     oldPassword, newPassword, confirmPassword
		// Returns:
		//     success and error: Action status
		'changeUserPassword' => array('event' => Event::USER_SET_PROFILE,
			'fn' => function ($user) {
				$chpw_param_props = array(
					'oldPassword' => array('type' => FORM_VALIDATE_NOTEMPTY),
					'newPassword' => array('type' => FORM_VALIDATE_NOTEMPTY),
					'confirmPassword' => array('type' => FORM_VALIDATE_OTHERFIELD,
						'field' => 'newPassword'));
				$chpw_params = array_keys($chpw_param_props);
				$params = params($chpw_params, $chpw_param_props, true);
				if (is_array($params)) {
					$oldPassword = $params['oldPassword'];
					$newPassword = $params['newPassword'];
					$confirmPassword = $params['confirmPassword'];
					if (password_verify($oldPassword, $user->getPassword())) {
						$user->changePassword($newPassword);
						return null;
					} else {
						return 'Incorrect password';
					}
				} else {
					return $params;
				}
			}),
		// Action:           fetchBuildings
		// Session required: logged in
		// Parameters:       none
		// Returns:
		//     objects: array of building names
		//     success and error: Action status
		'fetchBuildings' => array('event' => Event::USER_GET_PROFILE,
			'fn' => function ($user) {
				return Place::getAllBuildings();
			}),
		// Action:           fetchTheRooms
		// Session required: logged in
		// Parameters:
		//     building: name of building to look in
		// Returns:
		//     objects: array of room numbers (or names) in this building
		//     success and error: Action status
		'fetchTheRoom' => array('event' => Event::USER_GET_PROFILE, 
			'fn' => function ($user) {
				$params = params(array('building'));
				if (is_array($params)) {
					$places = Place::getPlacesByBuilding($params['building']);
					return array_map(function ($place) {
						return $place->getRoom();
					}, $places);
				} else {
					return $params;
				}
			}),
		// Action:           fetchUser
		// Session required: logged in
		// Parameters:
		//     userID: The user's ID
		//     userType: The expected user type (pass -1 for any)
		// Returns:
		//     object: The user's data
		//     success and error: Action status
		'fetchUser' => array('event' => Event::USER_GET_PROFILE,
			'fn' => function ($user) {
				$params = params(array('userID', 'userType'));
				if (is_array($params)) {
					$user = User::getUserByID($params['userID'], $params['userType']);
					if ($user == null) {
						return 'User not found';
					} else {
						return $user;
					}
				} else {
					return $params;
				}
			}),
		// Action:           fetchApplication
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     appID: The application ID
		// Returns:
		//     object: The application data
		//     success and error: Action status
		'fetchApplication' => array('event' => Event::USER_GET_PROFILE,
			'fn' => function ($user) {
				if ($user->getObjectType() == STUDENT) {
					return 'Permission denied';
				}
				$params = params(array('appID'));
				if (is_array($params)) {
					$application = Application::getApplicationByID($params['appID']);
					if ($application == null) {
						return 'Application not found';
					} else {
						return $application;
					}
				} else {
					return $params;
				}
			}),
		// Action:           fetchComments
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     userID: The user ID
		// Returns:
		//     objects: The comments
		//     success and error: Action status
		'fetchComments' => array('event' => Event::USER_GET_PROFILE,
			'fn' => function ($user) {
				if ($user->getObjectType() == STUDENT) {
					return 'Permission denied';
				}
				$params = params(array('userID'));
				if (is_array($params)) {
					$student = User::getUserByID($params['userID'], STUDENT);
					if ($student == null) {
						return 'Student not found';
					} else {
						$comments = $student->getAllComments();
						return $comments;
					}
				} else {
					return $params;
				}
			}),
		// Action:           setAppStatus
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     appID: The app ID
		//     decision: The new app status
		// Returns:
		//     success and error: Action status
		'setAppStatus' => array('event' => Event::NONSTUDENT_SET_APP,
			'fn' => function ($user) {
				if ($user->getObjectType() == STUDENT) {
					return 'Permission denied';
				}
				$params = params(array('appID', 'decision'));
				if (is_array($params)) {
					$decision = $params['decision'];
					$application = Application::getApplicationByID($params['appID']);
					if ($application == null) {
						return 'Application not found';
					} else {
						$application->setApplicationStatus($params['decision']);
						return null;
					}
				} else {
					return $params;
				}
			}),
		// Action:           newStudentComment
		// Session required: logged in (not STUDENT)
		// Parameters:
		//     studentID: referred-to student
		//     comment: comment text
		// Returns:
		//     success and error: Action status
		'newStudentComment' => array('event' => Event::NONSTUDENT_COMMENT,
			'fn' => function ($user) {
				if ($user->getObjectType() == STUDENT) {
					return 'Permission denied';
				}
				$params = params(array('studentID', 'comment'));
				if (is_array($params)) {
					$student = User::getUserByID($params['studentID'], STUDENT);
					if ($student == null) {
						return 'Student not found';
					} else {
						$student->saveComment($params['comment'], $user, time());
					}
				} else {
					return $params;
				}
			}),
		// Action:           searchForUsers
		// Session required: STAFF
		// Parameters:
		//     email: email field
		//     firstName: first name field
		//     lastName: last name field
		//     userTypes: STUDENT, PROFESSOR or -1 for either
		// Returns:
		//     success and error: Action status
		'searchForUsers' => array('event' => Event::USER_GET_PROFILE, 'userType' => STAFF,
			'fn' => function ($user) {
				$params = params(array('email','firstName','lastName','userTypes'), array(
					'email' => FORM_VALIDATE_IGNORE,
					'firstName' => FORM_VALIDATE_IGNORE,
					'lastName' => FORM_VALIDATE_IGNORE));

				if (is_array($params)) {
					$usersFound = User::findUsers($params['email'],$params['firstName'],
						$params['lastName'], -1);
					return $usersFound;
				} else {
					return $params;
				}
			}),
		'uploadTerm' => array('event' => Event::STAFF_TERM_IMPORT, 'userType' => STAFF,
			'fn' => function ($user) {
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

				$params = params(array('termYear', 'termSemester'), array(
					'termYear' => array('type' => FORM_VALIDATE_NUMSTR,
						'min_length' => 4, 'max_length' => 4)), true);
				if (is_array($params)) {
					if (!isset($_FILES['termFile'])) {
						return 'Invalid parameter (termFile)';
					} else {
						$upload = $_FILES['termFile'];
						switch ($upload['error']) {
						case UPLOAD_ERR_OK:
							$lines = @file($upload['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
							if ($lines) {
								Term::importTermFromCSV($params['termYear'], $params['termSemester'], $lines, $upload);
								break;
							} else {
								$upload['error'] = CUSTOM_UPLOAD_ERR_CANT_READ;
								// intentional fall-through
							}
						default:
							throw new TarsException(Event::ERROR_FORM_UPLOAD,
								Event::STAFF_TERM_IMPORT,
								$upload_error_message($upload['error']));
						}
					}
				} else {
					return $params;
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

