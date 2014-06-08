<?php
	include('db.php');
	
	$exists = emailExists($_POST['email']);
	
	echo json_encode(array('valid' => $exists));
?>