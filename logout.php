<?php

/*This file can be called from any page to securely end a session */

require_once 'actions.php';

Action::callAction('logout');
// TODO return['error'] show to user?

header("Location: ./");

