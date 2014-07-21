<?php

function email($user, $title, $body_text) {
	$domain = 'http://www.natembook.com/tars/';
	$firstName = $user->getFirstName();
	$lastName = $user->getLastName();
	$filName = $user->getFILName();
	$email = $user->getEmail();
	$emailTo = "$firstName $lastName <$email>";
	$fulltext = "<p>Hello $filName,</p>\n\n$body_text\n\n<p>TA Reporting System Staff<br>$domain</p>";
	//mail($emailTo, "TARS: $title", $fulltext);
}

function email_signup_token($userID, $selfCreated) {
	$user = User::getUserByID($userID);

	$token = $user->getPasswordToken();
	$token32 = base32_encode($token);
	$domain = 'http://www.natembook.com/tars/';

	switch ($user->getObjectType()) {
	case STUDENT: $usertype = 'student'; break;
	case PROFESSOR: $usertype = 'faculty'; break;
	case STAFF: $usertype = 'staff'; break;
	case ADMIN: $usertype = 'administrator'; break;
	}

	if ($selfCreated) {
		$body = "You have successfully created a $usertype TARS account with this e-mail address.";
	} else {
		$body = "A $usertype TARS account has been created for you with this e-mail address.";
	}
	$body2 = "Click the following link to confirm your email address: {$domain}confirmEmail.php?token=$token32";
	$body3 = 'If you think you have received this e-mail in error, you may safely ignore it.';
	email($user, 'Account Creation', "<p>$body</p>\n\n<p>$body2</p>\n\n<p>$body3</p>");
}

