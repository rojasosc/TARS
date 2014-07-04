<?php

require_once 'studentSession.php';
require_once '../db.php';
require_once '../formInput.php';
require_once '../error.php';

$error = null;
$form_args = get_form_values(array(
	'firstName', 'lastName', 'email', 'mobilePhone', 'classYear', 'major', 'gpa', 'universityID',
	'aboutMe'));

$invalid_values = get_invalid_values($form_args);
if (count($invalid_values) > 0) {
	$error = new TarsException(Event::ERROR_FORM_FIELD,
		Event::USER_SET_PROFILE, $invalid_values);
} else {
	try {
		$email = $form_args['email'];
		$student = User::getUserByEmail($email, STUDENT);
		if ($student) {
			$student->updateProfile($form_args['firstName'], $form_args['lastName'],
				$form_args['mobilePhone'], $form_args['classYear'],
				$form_args['major'], $form_args['gpa'],
				$form_args['universityID'], $form_args['aboutMe']);
		} else {
			$error = new TarsException(Event::ERROR_PERMISSION,
				Event::USER_SET_PROFILE);
		}
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR,
			Event::USER_SET_PROFILE, $ex);
	}
}

if ($error == null) {
	$result = array('success' => true);
} else {
	$result = array('success' => false, 'error' => $error->toArray());
}

echo json_encode($result);

