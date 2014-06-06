<?php
	include("../db.php");
	if(isset($_POST)) {
		$pID = $_POST['positionID'];
		$sID = $_POST['studentID'];
		$comp = $_POST['compensation'];
		$qual = $_POST['qualifications'];
		apply($pID, $sID, $comp, $qual);
	}
	echo "success";
?>