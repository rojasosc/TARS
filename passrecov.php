<?php

require_once 'actions.php';

$output = Action::callAction('passRecov', $_POST);

session_start();
$_SESSION['callbackResult'] = $output;

header('Location: ./');

