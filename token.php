<?php

require_once 'actions.php';

$output = Action::callAction('applyToken', $_REQUEST);

LoginSession::saveDataForRedirect($output);

// go to index
header('Location: ./');

