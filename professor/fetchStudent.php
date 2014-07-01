<?php
require_once('professorSession.php');
require_once('../db.php');
require_once('../formInput.php');
require_once('../error.php');

$error = null;
if (!isset($_POST['userID']))
	$error = new TarsException(Event::ERROR_FORM_FIELD, Event::USER_GET_PROFILE,
		array('userID'));
} else {
	try {
		$user = User::getUserByID($_POST['userID'], STUDENT);
		if (!$user) {
			$error = new TarsException(Event::ERROR_NOT_FOUND, Event::USER_GET_PROFILE);
		}
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::USER_GET_PROFILE, $ex);
	}
}

if ($error == null) {
	$result = array('success' => true, 'object' => array(
		'firstName' => $user->getFirstName(),
		'lastName' => $user->getLastName(),
		'email' => $user->getEmail(),
		'mobilePhone' => $user->getMobilePhone(),
		'classYear' => $user->getClassYear(),
		'major' => $user->getMajor(),
		'gpa' => $user->getGPA(),
		'aboutMe' => $user->getAboutMe()));
} else {
	$result = array('success' => false, 'error' => $error->toArray());
}

echo json_encode($result);

