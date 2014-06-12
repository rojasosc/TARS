<?php
	include('../db.php');
	if(!empty($_POST)) {
		$student = User::getUserByEmail($_POST['email'], STUDENT);
		$student->updateProfile($_POST['fn'], $_POST['ln'], $_POST['pn'], $_POST['mjr'], $_POST['year'], $_POST['gpa'], $_POST['qual-hist']);
	}
?>