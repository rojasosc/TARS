<?php
include('db.php');

$result = array('valid' => false);

if (isset($_POST['email'])) {
	try {
		$result['valid'] = User::checkEmailAvailable($_POST['email']);
	} catch (PDOException $ex) {
		// valid = false on error
		Error::setError(Error::EXCEPTION, 'Error checking e-mail availability.',
			$ex);
		$result['error'] = Error::getError()->toArray();
	}
}

echo json_encode($result);

