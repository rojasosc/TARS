<?php
<<<<<<< HEAD:student/withdraw.php
require_once 'studentSession.php';
require_once '../db.php';
require_once '../formInput.php';
require_once '../error.php';
=======
require_once '../studentSession.php';
<<<<<<< HEAD:student/withdraw.php
<<<<<<< HEAD:student/withdraw.php
require_once '/TARS/db.php';
require_once '/TARS/formInput.php';
require_once '/TARS/error.php';
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring:student/cur_pos/withdraw.php
=======
require_once '../../db.php';
require_once '../../formInput.php';
require_once '../../error.php';
>>>>>>> parent of f6dab39... refactored professor directory, tweaked student directory:student/cur_pos/withdraw.php
=======
require_once '/TARS/db.php';
require_once '/TARS/formInput.php';
require_once '/TARS/error.php';
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring:student/cur_pos/withdraw.php

$form_args = get_form_values(array('positionID'));

if ($error == null) {
	$invalid_values = get_invalid_values($form_args);
	if (count($invalid_values) > 0) {
		$error = new TarsException(Event::ERROR_FORM_FIELD, Event::STUDENT_WITHDRAW,
			$invalid_values);
	} else {
		try {
			$student->withdraw($form_args['positionID']);
		} catch (PDOException $ex) {
			$error = new TarsException(Event::SERVER_DBERROR, Event::STUDENT_WITHDRAW, $ex);
		}
	}
}

if ($error == null) {
	$result = array('success' => true);
} else {
	$result = array('success' => false, 'error' => $error->toArray());
}

echo json_encode($result);

