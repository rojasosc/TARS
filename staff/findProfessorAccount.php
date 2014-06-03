<?php

	include('../db.php');
	
	$professor = getProfessor($_POST['emailSearch']);

	echo json_encode($professor,true);
?>