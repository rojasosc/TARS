<?php
require_once '../db.php';
require_once '../session.php';

$error = null;
$success = session_start();
$staff = false;
if (!$success) {
	$error = new TarsException(Event::SERVER_EXCEPTION, Event::SESSION_CONTINUE,
		new Exception('Session continue failed'));
} else {
	try {
		$staff = Session::getLoggedInUser(STAFF);
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::SESSION_CONTINUE, $ex);
	}
}
if (!$staff) {
	if ($error == null) {
		$error = new TarsException(Event::ERROR_PERMISSION, Event::SESSION_CONTINUE,
			array('not staff'));
	}
}

