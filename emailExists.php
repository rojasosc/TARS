<?php
include('db.php');

$result = array('valid' => false);

if (isset($_POST['email'])) {
	$result['valid'] = User::checkEmailAvailable($_POST['email']);
}

echo json_encode($result);

