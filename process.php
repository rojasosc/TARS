<?php

require('db.php');
require('formInput.php');
require('error.php');
require('email.php');

$result = array('success' => false);
if (isset($_POST['submitButton'])) {
	$form_args = get_form_values(array(
		'email','emailConfirm','password','passwordConfirm','firstName','lastName',
		'mobilePhone','major','gpa','classYear','aboutMe','universityID'));

	$invalid_values = get_invalid_values($form_args);
	if (count($invalid_values) > 0) {
		Error::setError(Error::FORM_SUBMISSION, 'Error creating an account.',
			$invalid_values);
	} else {
		// manually validate emailConfirm and passwordConfirm fields for now...
		if ($form_args['email'] != $form_args['emailConfirm']) {
			Error::setError(Error::FORM_SUBMISSION, 'Error creating an account.',
				array('emailConfirm'));
		} elseif ($form_args['password'] != $form_args['passwordConfirm']) {
			Error::setError(Error::FORM_SUBMISSION, 'Error creating an account.',
				array('passwordConfirm'));
		} else {
			try {
				$studentID = Student::registerStudent(
					$form_args['email'], $form_args['password'],
					$form_args['firstName'], $form_args['lastName'],
					$form_args['mobilePhone'], $form_args['major'],
					$form_args['gpa'], $form_args['classYear'],
					$form_args['aboutMe'], $form_args['universityID']);
				// TODO: NYI email_signup_token($studentID, true);
				$result['success'] = true;
			} catch (PDOException $ex) {
				Error::setError(Error::EXCEPTION, 'Error creating an account.',
					$ex);
			}
		}
	}
}

if (!$result['success']) {
	$result['error'] = Error::getError()->toArray();
}
echo json_encode($result, true);

