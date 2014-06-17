<?php
include('../db.php');
$student = array();
if (isset($_POST['userID'])) {
	try {
		$user = User::getUserByID($_POST['userID']);
		if ($user) {
			$student['firstName'] = $user->getFirstName();
			$student['lastName'] = $user->getLastName();
			$student['email'] = $user->getEmail();
			$student['mobilePhone'] = $user->getMobilePhone();
			$student['classYear'] = $user->getClassYear();
			$student['major'] = $user->getMajor();
			$student['gpa'] = $user->getGPA();
			$student['aboutMe'] = $user->getAboutMe();
		}
	} catch (PDOException $ex) {
		Error::setError(Error::EXCEPTION, 'Error getting user object.', $ex);
		$student['error'] = Error::getError()->toArray();
	}
}
echo json_encode($student, true);

