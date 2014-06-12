<?php
	include('../db.php');
	$professor = User::getUserByEmail($_POST['email'],PROFESSOR);
	$professor->updateProfile($_POST['firstName'],$_POST['lastName'],$POST_['placeID'],$POST_['officePhone'],$_POST['mobilePhone']);	

?>