<?php
require_once('../db.php');
require_once('../session.php');

session_start();

$staff = Session::getLoggedInUser(STAFF);
if (!$staff) {
	// TODO: save errors for index
	header('Location: ../.');
	exit;
}

