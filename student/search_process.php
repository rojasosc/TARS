<?php
	include("../db.php");
	print_r($_POST);
	if(isset($_POST)) {
		$connect = open_database();
		$pID = mysqli_real_escape_string($connect, $_POST['positionID']);
		$sID = mysqli_real_escape_string($connect, $_POST['studentID']);
		$comp = mysqli_real_escape_string($connect, $_POST['compensation']);
		$qual = mysqli_real_escape_string($connect, $_POST['qualifications']);
		$sql = "INSERT IGNORE INTO Assistantship\n"
			."(`positionID`, `studentID`, `compensation`, `status`, `qualifications`)\n"
			."VALUES ('$pID', '$sID', '$comp', '0', '$qual');";
		echo $sql;
		mysqli_query($connect, $sql);
		close_database($connect);
	}
	
?>