<?php
 
 /*This file can be called from any page to securely end a session */

require_once 'session.php';

Session::destroy();

// TODO: save errors for index somehow?
header("Location: ./");
exit;

