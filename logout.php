<?php
 
 /*This file can be called from any page to securely end a session */

require_once 'session.php';
require_once 'actions.php';
require_once 'error.php';

try {
	Action::callAction('logout');
} catch (TarsException $ex) {
	// TODO show to user?
}

header("Location: ./");
exit;

