<?php
	include('../db.php');
	$courses = Course::getAllCourses();
	echo json_encode($courses,true);
?>