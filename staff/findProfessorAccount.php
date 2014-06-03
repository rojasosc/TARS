<?php

	include('../db.php');
	
	$professor = getProfessor($_POST['email']);

	echo json_encode($professor,true)
?>