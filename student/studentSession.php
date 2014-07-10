<?php
require_once '../db.php';
require_once '../session.php';

$error = null;
$success = session_start();
$student = false;
if (!$success) { //If the session failed to start, throw an exception and log the error
	$error = new TarsException(Event::SERVER_EXCEPTION, Event::SESSION_CONTINUE,
		new Exception('Session continue failed'));
} else {
	try { //If the session managed to start...
		$student = Session::getLoggedInUser(STUDENT); //Find out who logged in
	} catch (PDOException $ex) { //Catch any PDO exceptions and log them
		$error = new TarsException(Event::SERVER_DBERROR, Event::SESSION_CONTINUE, $ex);
	}
}
if (!$student) { //If the logged user is not a student
	if ($error == null) { //Log any errors
		$error = new TarsException(Event::ERROR_PERMISSION, Event::SESSION_CONTINUE,
			array('not student'));
	}
}

