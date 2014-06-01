<?php

	include('../db.php');
	
	session_start();
	session_regenerate_id(true);
	
	if(!isset($_SESSION['auth'])){
		header('Location: ../index.php');
	} else {
		$email = $_SESSION['email'];	//Extract the email from the session to fetch a student object
		$student = getStudent($email);	//Fetch said student object
		$fn = $student['firstName'];	//Fetch the first name
		$ln = $student['lastName'];		//Fetch the last name
		$brand = $fn[0].". ".$ln;		//Create a single variable to hold the brand at the far left of the navbar
	}
?>