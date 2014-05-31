<?php
    include('../db.php');
    
    session_start();
    session_regenerate_id(true);
	
    /* Does the user have an existing session? */
    if (!isset($_SESSION['auth'])) {
	
	/* redirect to login page */
	header('Location: ../index.php');

    }else{
	$email = $_SESSION['email'];
	
	/* Obtain professor associate array representation */	
	$professor = getProfessor($email);
	
	$firstName = $professor['firstName'];
	$lastName = $professor['lastName'];
	
	/* Used in the navbar brand */
	$nameBrand = $firstName[0].".".$lastName;
    }
?>