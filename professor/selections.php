<?php

	 	 
	 include('../dbinterface.php');
	 
	 foreach($_POST as $action){
		$IDs = explode(" ",$action);
		//print_r($IDs);
		$status = $IDs[0];	// (pending => 0) (approved => 1) (rejected => 2)
		$UID = $IDs[1];		// student ID 
		$TID = $IDs[2];		// TAship ID
		approveStudent($UID,$TID,$status);
	 }
	 
	 header ('Location: applicants.php');

?>