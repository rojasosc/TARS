<?php
require_once '../db.php';

// TODO: error handle
// TODO: session check

$appID = $_POST['applicationID'];
$decision = $_POST['decision'];
if($decision > 0){
	$app = Application::getApplicationByID($appID);
	$student = $app->getStudent();
	$position = $app->getPosition();
	Application::setPositionStatus($student, $position, $decision);
}

