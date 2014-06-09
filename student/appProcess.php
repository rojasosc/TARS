<?php
	include("../db.php");
	if(isset($_POST)) {
		echo 'post isset';
		$pID = $_POST['positionID'];
		$comp = $_POST['compensation'];
		$qual = $_POST['qualifications'];
		Student::apply($pID, $comp, $qual);
	}
?>