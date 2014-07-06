<?php
require_once 'db.php';

$result = array('valid' => false);

if (isset($_POST['email'])) {
	try {
		$result['valid'] = User::checkEmailAvailable($_POST['email']);
	} catch (PDOException $ex) {
		// valid = false on error
		$error = new TarsException(Event::SERVER_DBERROR, Event::USER_CHECKEMAIL, $ex);
		$result['error'] = $error->toArray();
	}
}

echo json_encode($result);

