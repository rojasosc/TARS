<?php
require_once '../db.php';
require_once '../session.php';

<<<<<<< HEAD
	include('../db.php');
	
	session_start();
	
	if(!isset($_SESSION['auth'])){
		header('Location: ../index.php');
		exit;
	} else {
		$email = $_SESSION['email'];	//Extract the email from the session to fetch a student object
		$student = User::getUserByEmail($email, STUDENT);	//Fetch said student object
		if (!$student) {
			header('Location: ../index.php');
			exit;
		}
		$fn = $student->getFirstName();	//Fetch the first name
		$ln = $student->getLastName();		//Fetch the last name
		$brand = $fn[0].". ".$ln;		//Create a single variable to hold the brand at the far left of the navbar
		$sID = $student->getID();
=======
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
>>>>>>> origin/stage
	}
}
if (!$student) { //If the logged user is not a student
	if ($error == null) { //Log any errors
		$error = new TarsException(Event::ERROR_PERMISSION, Event::SESSION_CONTINUE,
			array('not student'));
	}
}

