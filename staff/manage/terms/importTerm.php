<?php
require_once '../../staffSession.php';
require_once '../../../formInput.php';
require_once '../../../error.php';

$error = null;
$form_args = get_form_values(array('termYear', 'termSemester'));

$invalid_values = get_invalid_values($form_args);
if (count($invalid_values) > 0) {
	$error = new TarsException(Event::ERROR_FORM_FIELD, Event::STAFF_TERM_IMPORT,
		$invalid_values);
} elseif (!isset($_FILES['termFile'])) {
	$error = new TarsException(Event::ERROR_FORM_FIELD, Event::STAFF_TERM_IMPORT,
		array('termFile'));
} else {
	$upload = $_FILES['termFile'];
	switch ($upload['error']) {
	case UPLOAD_ERR_OK:
		// success
		try {
			$lines = @file($upload['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			if ($lines) {
				try {
					Term::importTermFromCSV($form_args['termYear'], $form_args['termSemester'],
						$lines, $upload);
				} catch (PDOException $ex) {
					$error = new TarsException(Event::SERVER_DBERROR, Event::STAFF_TERM_IMPORT,
						$ex);
				}
			} else {
				$error = new TarsException(Event::ERROR_FORM_UPLOAD, Event::STAFF_TERM_IMPORT,
					$upload);
			}
		} catch (TarsException $ex) {
			$error = $ex;
		}
		break;
	default:
		$error = new TarsException(Event::ERROR_FORM_UPLOAD, Event::STAFF_TERM_IMPORT,
			$upload);
		break;
	}
}

if ($error == null) {
	$result = array('success' => true);
} else {
	$result = array('success' => false, 'error' => $error->toArray());
}

echo json_encode($result);

