<?php

require_once('db.php');
require_once('formInput.php');
require_once('error.php');
require_once('email.php');

$error = null;
if (isset($_POST['submitButton'])) {
	$form_args = get_form_values(array(
		'email','emailConfirm','password','passwordConfirm','firstName','lastName',
		'mobilePhone','major','gpa','classYear','aboutMe','universityID'));

	$invalid_values = get_invalid_values($form_args);
	if (count($invalid_values) > 0) {
		$error = new TarsException(Event::ERROR_FORM_FIELD,
			Event::USER_CREATE, $invalid_values);
	} else {
		// manually validate emailConfirm and passwordConfirm fields for now...
		if ($form_args['email'] != $form_args['emailConfirm']) {
			$error = new TarsException(Event::ERROR_FORM_FIELD,
				Event::USER_CREATE, array('emailConfirm'));
		} elseif ($form_args['password'] != $form_args['passwordConfirm']) {
			$error = new TarsException(Event::ERROR_FORM_FIELD,
				Event::USER_CREATE, array('passwordConfirm'));
		} else {
			try {
				$studentID = Student::registerStudent(
					$form_args['email'], $form_args['password'],
					$form_args['firstName'], $form_args['lastName'],
					$form_args['mobilePhone'], $form_args['major'],
					$form_args['gpa'], $form_args['classYear'],
					$form_args['aboutMe'], $form_args['universityID']);
				// TODO: NYI email_signup_token($studentID, true);
			} catch (PDOException $ex) {
				$error = new TarsException(Event::SERVER_PDOERR,
					Event::USER_CREATE, $ex);
			}
		}
	}
}

$result = array('success' => $error == null);
if ($error != null) {
	$result['error'] = $error->toArray();
}

echo json_encode($result);

