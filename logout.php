<?php
 
 /*This file can be called from any page to securely end a session */

require_once 'session.php';
require_once 'actions.php';
require_once 'error.php';

Action::callAction('logout');
// TODO return['error'] show to user?

header("Location: ./");
exit;

