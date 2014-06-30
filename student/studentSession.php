<?php

	require_once('../db.php');
	
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
	}
