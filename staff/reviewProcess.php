<?php
	include('../db.php');
	ini_set('display_errors',1);
	Student::setStudentStatus($_POST['userID'],$_POST['status']);
?>
