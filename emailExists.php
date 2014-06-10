<?php
	include('db.php');
	
	$exists = emailAvailable($_POST['email']);
	
	echo json_encode(array('valid' => $exists));
?>
