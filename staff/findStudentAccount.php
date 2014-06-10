<?php

	include('../db.php');
	
	
	/* Retrieve results based on the user's query */
	/* email: the target's email.
	/* firstName: the target's first name.
	/* lastName: the target's last name. */
	
	
	$student = getStudent($_POST['emailSearch']);
	echo json_encode($student,true);
?>