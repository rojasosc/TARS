<?php
require_once('../db.php');
require_once('../session.php');

session_start();

$student = Session::getLoggedInUser(STUDENT);
if (!$student) {
	// TODO: save errors for index
	header('Location: ../.');
	exit;
}

