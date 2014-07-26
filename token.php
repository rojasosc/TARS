<?php
require_once 'actions.php';
$output = Action::callAction('applyToken', $_REQUEST);
session_start();
$_SESSION['tokenResult'] = $output;
Header('Location: index.php');

