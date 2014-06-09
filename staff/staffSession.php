<?php

	include('../db.php');
	
	session_start();
	session_regenerate_id(true);
	
	if(!isset($_SESSION['auth'])){
		header('Location: ../index.php');
	} else {
		$email = $_SESSION['email'];	//Extract the email from the session to fetch a staff object
		$staff = getStaff($email);		//Fetch said staff object
		$fn = $staff->getFirstName();	//Fetch the first name
		$ln = $staff->getLastName();		//Fetch the last name
		$nameBrand = $fn[0].". ".$ln;		//Create a single variable to hold the brand at the far left of the navbar
	}
?>
