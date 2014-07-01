<?php
require_once('db.php');

$result = array('valid' => false);

if (isset($_POST['email'])) {
	try {
		$result['valid'] = User::checkEmailAvailable($_POST['email']);
	} catch (PDOException $ex) {
		// valid = false on error
		$result['error'] = (new TarsException(Event::SERVER_DBERROR, Event::USER_CHECKEMAIL, $ex)
			->toArray();
	}
}

echo json_encode($result);

