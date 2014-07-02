<?php
require_once('../db.php');
require_once('../session.php');

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
		$error = new TarsException(Event::PERMISSION_DENIED, Event::SESSION_CONTINUE,
			array('not professor'));
	}
}

