<?php
    include('../db.php');
    
    session_start();
	
    /* Does the user have an existing session? */
    if (!isset($_SESSION['auth'])) {
	
		/* redirect to login page */
		header('Location: ../index.php');
		exit;

    }else{
		$email = $_SESSION['email'];
		
		/* Obtain professor associate array representation */	
		$professor = User::getUserByEmail($email, PROFESSOR);

		if (!$professor) {
			/* redirect to login page if not a professor */
			header('Location: ../index.php');
			exit;
		}
    }

