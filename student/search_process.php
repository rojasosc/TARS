<?php
	include("../db.php");
	
	if(isset($_POST['positionID'] && $_POST['studentID'] && $_POST['compensation']) {
		$connect = open_database();
		$pID = $_POST['positionID'];
		$sID = $_POST['studentID'];
		$comp = $_POST['compensation'];
		$sql = "INSERT IGNORE INTO Assistantships\n"
			."(positionID, studentID, compensation, status)\n"
			."VALUES ('$pID', '$sID', '$comp', '0');";
		mysqli_query($connect, $sql);
		close_database($connect);
	}
	
?>