<?php
	include('../db.php');
	$buildings = Place::getBuildings();
	echo json_encode($buildings);
?>