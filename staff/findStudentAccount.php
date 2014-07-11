<?php
	require_once '../db.php';
	
	
	/* Retrieve results based on the user's query */
	/* email: the target's email.
	/* firstName: the target's first name.
	/* lastName: the target's last name. */
	
	
	$student = User::getUserByEmail($_POST['emailSearch'], STUDENT);
	echo json_encode($student,true);

