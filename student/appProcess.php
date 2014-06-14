<?php
require('studentSession.php');
require('../formInput.php');
require('../error.php');

$form_args = get_form_values(array(
	'positionID','studentID','compensation','qualifications'));

$result = array('success' => false);
$e_msg = 'Error applying to position.';

// check that we are logged in as the given studentID
if ($student->getID() != $form_args['studentID']) {
	Error::setError(Error::PERMISSION_DENIED, $e_msg);
} else {
	// check that the fields are not empty
	$invalid_values = get_invalid_values($form_args);
	if (count($invalid_values) > 0) {
		Error::setError(Error::FORM_SUBMISSION, $e_msg,
			$invalid_values);
	} else {
		try {
			// get the given studentID's object
			$student = User::getUserByID($form_args['studentID'], STUDENT);
			if (!$student) {
				Error::setError(Error::PERMISSION_DENIED, $e_msg);
			} else {
				// apply to the given position
				$student->apply($form_args['positionID'],
					$form_args['compensation'],
					$form_args['qualifications']);

				$result['success'] = true;
			}
		} catch (PDOException $ex) {
			Error::setError(Error::EXCEPTION, $e_msg, $ex);
		}
	}
}

if (!$result['success']) {
	$result['error'] = Error::getError()->toArray();
}

echo json_encode($result, true);

