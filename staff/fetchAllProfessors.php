<?php
	include('../db.php');
	$professors = Professor::getAllProfessors();
	$profArray = array();
	foreach($professors as $professor){
		$profArray[] = array('firstName' => $professor->getFirstName(), 'lastName' => $professor->getLastName());
	}
	echo json_encode($profArray,true);
?>