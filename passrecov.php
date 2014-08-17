<?php

require_once 'actions.php';

$output = Action::callAction('passRecov', $_POST);

LoginSession::saveDataForRedirect($output);

// go to index
header('Location: ./');

