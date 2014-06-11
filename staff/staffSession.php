<?php

	include('../db.php');
	
	session_start();
	
	if(!isset($_SESSION['auth'])){
		header('Location: ../index.php');
		exit;
	} else {
		$email = $_SESSION['email'];	//Extract the email from the session to fetch a staff object
		$staff = User::getUserByEmail($email, STAFF);		//Fetch said staff object
		if (!$staff) {
			header('Location: ../index.php');
			exit;
		}
		$fn = $staff->getFirstName();	//Fetch the first name
		$ln = $staff->getLastName();		//Fetch the last name
		$nameBrand = $fn[0].". ".$ln;		//Create a single variable to hold the brand at the far left of the navbar
	}

