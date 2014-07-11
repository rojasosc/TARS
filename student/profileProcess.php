<?php

require_once 'studentSession.php';
require_once '/TARS/db.php';
require_once '/TARS/formInput.php';
require_once '/TARS/error.php';

$error = null;
/*
 * Fetch all the POST arguments from the profile form to be used to modify the database entry
 * that corresponds with the student.
 */

$form_args = get_form_values(array(
	'firstName', 'lastName', 'email', 'mobilePhone', 'classYear', 'major', 'gpa', 'universityID',
	'aboutMe'));
/*
 * A single function call wrapped in layers of error catching and event logging
 * updateProfile is called to send SQL queries to the database to modify it with changes
 */
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

