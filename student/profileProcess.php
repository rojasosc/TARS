<?php

require_once('studentSession.php');
require_once('../db.php');
require_once('../formInput.php');
require_once('../error.php');

$error = null;
if (isset($_POST['submitButton'])) {
	$form_args = get_form_values(array(
		'firstName', 'lastName', 'mobilePhone', 'major', 'classYear', 'gpa', 'aboutMe',
		'universityID'));

	$invalid_values = get_invalid_values($form_args);
	if (count($invalid_values) > 0) {
		$error = new TarsException(Event::ERROR_FORM_FIELD,
			Event::USER_SETPROFILE, $invalid_values);
	} else {
		try {
			$student = User::getUserByEmail($email, STUDENT);
			if ($student) {
				$student->updateProfile($form_args['firstName'], $form_args['lastName'],
					$form_args['mobilePhone'], $form_args['major'], $form_args['gpa'],
					$form_args['classYear'], $form_args['aboutMe'], $form_args['universityID']);
			} else {
				$error = new TarsException(Event::ERROR_PERMISSION,
					Event::USER_SETPROFILE);
			}
		} catch (PDOException $ex) {
			$error = new TarsException(Event::SERVER_PDOERR,
				Event::USER_SETPROFILE, $ex);
		}
	}
}

if ($error == null) {
	$result = array('success' => true);
} else {
	$result = array('success' => false, 'error' => $error->toArray());
}

echo json_encode($result);

