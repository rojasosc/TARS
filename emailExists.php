<?php
	include('db.php');
	$email = $_POST['email'];
	$exists = User::checkEmailAvailable($email);
	echo json_encode(array('valid' => $exists));
?>
