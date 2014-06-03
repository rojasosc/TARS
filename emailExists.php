<?php

	include('db.php');
	
	$exists = emailExists($_POST['email']);
	
	echo  $exists;

?>