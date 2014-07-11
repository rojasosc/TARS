<?php
<<<<<<< HEAD
require_once '../db.php';
require_once '../session.php';
=======
require_once '/TARS/db.php';
require_once '/TARS/session.php';
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring

$error = null;
$success = session_start();
$professor = false;
if (!$success) {
	$error = new TarsException(Event::SERVER_EXCEPTION, Event::SESSION_CONTINUE,
		new Exception('Session continue failed'));
} else {
	try {
		$professor = Session::getLoggedInUser(PROFESSOR);
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::SESSION_CONTINUE, $ex);
	}
}
if (!$professor) {
	if ($error == null) {
		$error = new TarsException(Event::ERROR_PERMISSION, Event::SESSION_CONTINUE,
			array('not professor'));
	}
}

