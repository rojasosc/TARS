<?php
	include('../db.php');
	ini_set('display_errors',1);
	Feedback::newComment($_POST['studentID'],$_POST['commenterID'],$_POST['comment']);
?>