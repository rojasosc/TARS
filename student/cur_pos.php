<?php  
require_once 'studentSession.php';

$error = null;
$term = null;
$positions = array();
$currentApps = array();
try {
	$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
	if ($currentTermID != null) {
		$term = Term::getTermByID($currentTermID);
	}

	$positions = $student->getApplications($term, APPROVED);
	$currentApps = $student->getApplications($term, PENDING);
} catch (PDOException $ex) {
	$error = new TarsException(Event::SERVER_DBERROR,
		Event::USER_GET_POSITIONS, $ex);
}
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>TARS</title>
		<!-- BEGIN CSS -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="student.css" rel="stylesheet">
		<link href="cur_pos.css" rel="stylesheet">
		<!-- END CSS -->
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="cur_pos.js"></script>
		<!-- END Scripts -->
		
	</head>
  
	<body>
		<!-- BEGIN Release Modal -->
		<div class="modal fade" id="releaseModal" tabindex="-1" role="dialog" aria-labelledby="releaseModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Release From Position</h1>
					</div>
					<div class="modal-body">
						<form action="withdraw.php" method="post" id="releaseForm">
							<fieldset>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											Are you sure you want to be released from this position? It is highly unlikely for you to get it back after you're released from it. You will no longer be responsible for this position but you will also relinquish all remaining compensations for filling this position.
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											If you still wish to be released from this position, an E-mail will be sent to your employer notifying them of your release and your reasons detailed below:
										</p>
										<textarea class="form-control" rows="8" cols="64" form="releaseForm" name="releaseReasons" id="releaseReasons"></textarea>
									</div>
								</div>
								<input name="studentID" id="studentID" type="hidden" value="<?=$student->getID()?>" />
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelRelease">Cancel</button>
						<button type="submit" class="btn btn-success" form="releaseForm" id="#releaseConfirm" value="Submit">Release</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Release Modal -->
		<!-- BEGIN Withdraw Modal -->
		<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Withdraw Application</h1>
					</div>
					<div class="modal-body">
						<form action="withdraw.php" method="post" id="withdrawForm">
							<fieldset>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											Are you sure you want to withdraw your application?
										</p>
									</div>
								</div>
								<input id="studentID" type="hidden" value="<?=$student->getID()?>" />
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal" id="withdrawCancel">Cancel</button>
						<button type="submit" class="btn btn-success" form="withdrawForm" id="#withdrawConfirm" value="Submit">Withdraw</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Release Modal -->
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Home
$header_active = 'curp';
require 'header.php';
?>
			<!-- BEGIN Page Content -->
			<div id="content">
<?php
if ($error != null) {
	echo $error->toHTML();
}
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
				<div class="panel panel-primary"> 
					<div class="panel-heading">
						<h1 class="panel-title">My Current Positions</h1>
					</div>
					<div class="panel-body">	
						<!-- BEGIN Current Positions Table -->
						<table class="table table-striped">
							<tr>
								<th class="hidden-xs">Position ID</th>
								<th>Course Number</th>
								<th class="hidden-xs">Course Name</th>
								<th>Type</th>
								<th>Location</th>
								<th>Time</th>
								<th class="hidden-xs">Compensation</th>
								<th>Withdraw</th>
							</tr>
							<?php
		foreach($positions as $row) {
			$position = $row->getPosition();
			$section = $position->getSection();
							?>
							<tr>
								<td class="positionID hidden-xs"><?= $position->getID()?></td>
								<td><?= $section->getCourseName()?></td>
								<td class="hidden-xs"><?= $section->getCourseTitle()?></td>
								<td><?= $position->getTypeTitle()?></td>
								<td><?= "TBD"?></td>
								<td><?= "TBD"?></td>
								<td class="hidden-xs"><?= $row->getCompensation()?></td>
								<td><a class="btn btn-default releaseButton" href="#releaseModal" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span></a></td>
							</tr>
							<?php
		}
							?>
						</table>
						<!-- END Current Positions Table -->
					</div>
				</div>
				<div class="panel panel-primary"> 
					<div class="panel-heading">
						<h1 class="panel-title">My Pending Applications</h1>
					</div>
					<div class="panel-body">	
						<!-- BEGIN Current Positions Table -->
						<table class="table table-striped">
							<tr>
								<th class="hidden-xs">Position ID</th>
								<th>Course Number</th>
								<th class="hidden-xs">Course Name</th>
								<th>Type</th>
								<th>Location</th>
								<th>Time</th>
								<th class="hidden-xs">Compensation</th>
								<th>Withdraw</th>
							</tr>
							<?php
		foreach($currentApps as $app) {
			$appPosition = $app->getPosition();
			$appSection = $appPosition->getSection();
							?>
							<tr>
								<td class="positionID hidden-xs"><?= $appPosition->getID()?></td>
								<td><?= $appSection->getCourseName()?></td>
								<td class="hidden-xs"><?= $appSection->getCourseTitle()?></td>
								<td><?= $appPosition->getTypeTitle()?></td>
								<td><?= "TBD"?></td>
								<td><?= "TBD"?></td>
								<td class="hidden-xs"><?= $app->getCompensation()?></td>
								<td><a class="btn btn-default withdrawButton" href="#withdrawModal" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span></a></td>
							</tr>
							<?php
		}
							?>
						</table>
						<!-- END Current Positions Table -->
					</div>
				</div>
<?php
}
?>
			</div>
			<!-- END Page Content --> 
	    
			<!--BEGIN Page Footer -->
			<div id="footer">
			</div>
			<!--END Page Footer -->
	
		</div> 
		<!-- End page-wrapper -->
	</body>
</html>
