<?php

require_once 'db.php';
require_once 'formInput.php';
require_once 'error.php';
require_once 'email.php';

$error = null;
$form_args = get_form_values(array(
	'email','emailConfirm','password','passwordConfirm','firstName','lastName',
	'mobilePhone','classYear','major','gpa','universityID','aboutMe'));

$invalid_values = get_invalid_values($form_args, array(
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
	'aboutMe' => array('type' => FORM_VALIDATE_NOTEMPTY)));
if (count($invalid_values) > 0) {
	$error = new TarsException(Event::ERROR_FORM_FIELD,
		Event::USER_CREATE, $invalid_values);
} else {
	try {
		$studentID = Student::registerStudent(
			$form_args['email'], $form_args['password'],
			$form_args['firstName'], $form_args['lastName'],
			$form_args['mobilePhone'], $form_args['classYear'],
			$form_args['major'], $form_args['gpa'],
			$form_args['universityID'], $form_args['aboutMe']);
		// TODO: NYI email_signup_token($studentID, true);
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR,
			Event::USER_CREATE, $ex);
	}
}

if ($error == null) {
	$result = array('success' => true);
} else {
	$result = array('success' => false, 'error' => $error->toArray());
}

echo json_encode($result);

