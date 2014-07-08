<?php
require_once 'professorSession.php';	

$term = null;
$sections = array();
if ($error == null) {
	try {
		$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
		if ($currentTermID != null) {
			$term = Term::getTermByID($currentTermID);
			$sections = $professor->getSections();
		}
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::USER_GET_SECTIONS, $ex);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<title>My Applicants</title>		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="applicants.js"></script>
		<script src="comments.js"></script>
		<script src="../js/tars_utilities.js"></script>
	</head> 
	<body>
		<!-- Profile Modal -->
		<div class="modal fade profile-modal" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="studentProfileModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h2 class="modal-title" id="studentModalTitle"></h2>
					</div>
					<div class="modal-body">
						<h3>Qualifications</h3>
						<div class="container qualifications">
						</div> <!-- End container -->									
						<h3>Academic Information</h3>
						<div class="container">
							<p id="studentMajor"></p>
							<p id="studentGPA"></p>
							<p id="studentClassYear"></p>
						</div> <!-- End container -->								
						<h3>Contact Information</h3>
						<div class="container">
							<p id="studentEmail"></p>
							<p id="studentMobilePhone"></p>
						</div> <!-- End container -->
					</div> <!-- End modal body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div> <!-- End modal-footer -->
				</div> <!-- End modal-content -->
			</div> <!-- End modal-dialog -->
		</div> <!-- End modal fade -->
		<!-- End Profile Modal -->

		 <!-- BEGIN Comment Modal-->
		<div class="modal fade comments-modal" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Reviews</h1>
					</div> <!-- End modal-header -->
					<div class="modal-body comments-block">
					</div> <!-- End modal-body -->
					<div class="modal-footer">
						<button class="btn btn-danger" data-dismiss="modal">Close</button>
					</div> <!-- End modal-footer -->				
				</div> <!-- End modal-content -->
			</div> <!-- End modal-dialog -->
		</div> <!-- End modal fade -->	
		<!-- END Comment Modal-->

		<!-- BEGIN page-wrapper -->
		<div id="page-wrapper">

<?php
// Display header for Applicants
$header_active = 'appl';
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
				<div class="row">
					<h1 class="panelHeader">My Applicants</h1>
				</div> <!-- End row -->			
							
				<?php
				/*Obtain positionIDS that are in the Assistantship table
				and that match a particular CRN	
				Pack these applications into a panel... repeat.
				 */
				$tableEntry = 0;
				foreach($sections as $section){
					$applications = array();
					try {
						/* applications for this particular section */
						$applications = Application::getApplications($section, $professor, $term, PENDING);
					} catch (PDOException $ex) {
						$error = new TarsException(Event::SERVER_DBERROR,
							Event::USER_GET_APPLICATIONS, $ex);
						echo $error->toHTML();
					}

					/* create a new panel */ 
					$panelID = "panelID" . $section->getID();

					/*TODO: Get total positions of a particular type and course.
					For instance, all the graders for CSC 172.*/

					$positionTypes = Position::getAllPositionTypes(true);
					$positionTotals = array();
					foreach ($positionTypes as $typeID => $title) {
						$data = array(
							'typeID' => $typeID,
							'title' => $title,
							'total' => $section->getTotalPositionsByType(
								$professor, $typeID),
							'current' => $section->getCurrentPositionsByType(
								$professor, $typeID));
						if ($data['total'] > 0) {
							$data['ratio'] = $data['current'] / $data['total']; 
							if ($data['ratio'] == 1) {
								$data['alert'] = 'success';
							} elseif ($data['ratio'] == 0) {
								$data['alert'] = 'danger';
							} else {
								$data['alert'] = 'warning';
							}
							$positionTotals[] = $data;
						}
					}

					$appCount = count($applications);
					if ($appCount == 0) {
						$appCount = '';
					}
					
					/*TODO: Mark workshop super leader positions in the db so that we 
					 can highlight them on professor pages. */
					 
					 /* Determine the color of the progress bars based on current/total ratio */
				?>
				<div class="row">
					<div class="container">
						<div class="panel panel-primary">
							<div class="panel-heading" data-toggle="collapse" data-target="#<?=$panelID?>">
							<h4 class="panel-title panelHeader"><?= $section->getCourseName() ?> <small><?=$section->getCourseTitle()?></small><span class="badge alert-warning pull-left"><?=$appCount?></span><span class="glyphicon glyphicon-chevron-right pull-right"></span></h4>
							</div> <!-- End panel-heading -->
							<div class="collapse panel-collapse" id="<?= $panelID ?>">
								<div class="panel-body">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>University ID</th>
												<th>First Name</th>
												<th>Last Name</th>
												<th>Email</th>
												<th>Type</th>
												<th>Application</th>
												<th>Reviews</th>
												<th>Action</th>
											</tr>
										</thead>
										<?php
										
										/* Insert each application */
										foreach($applications as $application){
											$student = $application->getStudent();
											$position = $application->getPosition();
											$profileID = "myProfile" . $student->getID();
											
										?>
										<tr><td><?= $student->getUniversityID() ?></td> <td><?= $student->getFirstName() ?></td> <td><?= $student->getLastName() ?></td><td><?= $student->getEmail() ?></td><td><?= $position->getTypeTitle() ?></td>
											<td><button data-toggle="modal" data-target="#profile-modal" data-appID="<?= $application->getID() ?>" data-usertype="<?= STUDENT ?>" data-userid="<?= $student->getID() ?>" class="btn btn-default circle profile">
											<span class="glyphicon glyphicon-file"></span></button>
											</td>
											<td>
												<button data-toggle="modal" data-target="#commentsModal" data-commenterID="<?= $professor->getID() ?>" data-userID="<?= $student->getID() ?>" class="btn btn-default comments">
												<span class="glyphicon glyphicon-comment"></span></button>
											</td>
											<td>	
												<form>
												<div class="btn-group" data-toggle="buttons" data-applicationID="<?= $application->getID() ?>">
													<label name="selection" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Approve">
														<input type="radio" name="<?= $applicationID ?>" id="approve" value="<?= APPROVED ?>"><span class="glyphicon glyphicon-ok"></span>
													</label>
													<label name="selection" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Deny">
														<input type="radio" name="<?= $applicationID ?>" id="deny" value="<?= REJECTED ?>"><span class="glyphicon glyphicon-remove"></span>
													</label>
													<label name="selection" class="btn btn-info active" data-toggle="tooltip" data-placement="bottom" title="Postpone">
														<input type="radio" name="<?= $applicationID ?>" id="postpone" value="0" checked><span class="glyphicon glyphicon-time" ></span>												
													</label>
												</div> <!-- End btn-group -->
												</form>
											</td>
										</tr> 											
											
											<?php
											/* Table entry closing brace */
											$tableEntry++;
											}
											
											?>
									</table> <!-- End table -->
								</div> <!-- End panel-body -->									
							</div> <!-- End collapse panel-collapse -->
							<div class="panel-footer">
								<div class="row">
<?php
					$totalsColumnWidth = 12;
					while ($totalsColumnWidth > 0 &&
						count($positionTotals) * $totalsColumnWidth > 12) {
						$totalsColumnWidth--;
					}
					foreach ($positionTotals as $totalBar) {
?>
									<div class="col-xs-<?=$totalsColumnWidth?>">
										<strong><?=$totalBar['title']?>s</strong>
										<div class="progress progress-striped active">
											<div class="progress-bar progress-bar-<?= $totalBar['alert'] ?>"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?= $totalBar['ratio'] * 100 ?>%">
											</div> <!-- End progress-bar progress-bar-danger -->
										</div> <!-- End progress-bar -->												
									</div> <!-- End column -->
<?php
					}
?>
								</div> <!-- End row -->
<?php
					if ($appCount > 0) {
?>
								<div class="row">
									<div class="col-xs-4">
										<button name="applyDecision" data-panelid="<?=$panelID?>" class="btn btn-success decisions"><span class="glyphicon glyphicon-ok-circle"></span> Submit Decisions</button>												
									</div> <!-- End column -->
								</div> <!-- End row -->
<?php
					}
?>
							</div> <!-- End panel-footer -->
						</div> <!-- End panel panel-primary -->
					</div> <!-- End container -->
				</div> <!-- End row -->
				<?php
				/* Course panels closing brace */
				}
				if (count($sections) == 0) {
?>
<div class="alert alert-info">There are no sections assigned to you this term.</div>
<?php
				}				
				?>

				<!-- END Course Panels -->
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
	
