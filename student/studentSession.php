<?php

	include('../db.php');
	
	session_start();
	
	if(!isset($_SESSION['auth'])){
		header('Location: ../index.php');
		exit;
	} else {
		$email = $_SESSION['email'];	//Extract the email from the session to fetch a student object
		$student = getStudent($email);	//Fetch said student object
		if (!$student) {
			header('Location: ../index.php');
			exit;
		}
		$fn = $student->getFirstName();	//Fetch the first name
		$ln = $student->getLastName();		//Fetch the last name
		$brand = $fn[0].". ".$ln;		//Create a single variable to hold the brand at the far left of the navbar
	}

