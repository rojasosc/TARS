<?php
	include('../db.php');
	ini_set('display_errors',1);
	$places = Place::getPlacesByBuilding($_POST['building']);
	$rooms = array();
	foreach($places as $room){
		$rooms[] = $room->getRoom();
	}
	echo json_encode($rooms);
?>