<?php
<<<<<<< HEAD:student/appProcess.php
require_once 'studentSession.php';
require_once '../formInput.php';
require_once '../error.php';
=======
require_once '../studentSession.php';
require_once '/TARS/formInput.php';
require_once '/TARS/error.php';
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring:student/search/appProcess.php

$form_args = get_form_values(array(
	'positionID','compensation','qualifications'));

if ($error == null) {
	// check that the fields are not empty
	$invalid_values = get_invalid_values($form_args);
	if (count($invalid_values) > 0) {
		$error = new TarsException(Event::ERROR_FORM_FIELD, Event::STUDENT_APPLY,
			$invalid_values);
	} else {
		try {
			// apply to the given position
			// use the studentSession $student object
			$student->apply($form_args['positionID'],
				$form_args['compensation'],
				$form_args['qualifications']);
		} catch (PDOException $ex) {
			$error = new TarsException(Event::SERVER_DBERROR, Event::STUDENT_APPLY, $ex);
		}
	}
}

if ($error == null) {
	$result = array('success' => true);
} else {
	$result = array('success' => false, 'error' => $error->toArray());
}

echo json_encode($result);

