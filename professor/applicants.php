<?php
require_once '../session.php';

$error = null;
$professor = null;
try {
	$professor = Session::start(PROFESSOR);
} catch (TarsException $ex) {
	$error = $ex;
}

$term = null;
$courses = array();
$sections = array();
if ($error == null) {
	try {
		$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
		if ($currentTermID != null) {
			$term = Term::getTermByID($currentTermID);
			$courses = $professor->getCourses($term);
		}
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::USER_GET_SECTIONS, $ex);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>My Applicants</title>

		<link href="../css/bootstrap-select.min.css" rel="stylesheet"/>
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="professor.css" rel="stylesheet"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap-select.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
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
						<div id="profileAlertHolder"></div>
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
					<div class="modal-body">
						<div id="commentsAlertHolder"></div>
						<div class="comments-block"></div>
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
				<div id="alertHolder">
<?php
if ($error != null) {
	echo $error->toHTML();
}
?>
				</div>
<?php
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
				<div class="row">
					<h1 class="panelHeader">My Applicants</h1>
				</div> <!-- End row -->			
				<div class="container">
				<?php
					foreach($courses as $course) {
						$sections = $professor->getSectionsByCourseID($course['courseID']);
						$applications = Application::getApplicationsByCourseID($course['courseID'],$professor);
						$panelHeading = $course['department'] . " " . $course['courseNumber'] . " " . $course['courseTitle'];
						$panelID = "course".$course['courseNumber'];
						?>
						<div class="panel panel-primary">
							<div class="panel-heading" data-toggle="collapse" data-target="#<?= $panelID ?>">
								<h4 class="panel-title panelHeader"><?= $panelHeading ?> <span class="glyphicon glyphicon-chevron-right pull-right"></span></h4>
							</div> <!-- End panel-heading -->
							<div class="collapse panel-collapse" id="<?= $panelID ?>">
								<div class="panel-body">
									
						<?php
							foreach($sections as $section){
								$sessions = $section->getAllSessions();
								$applications = array();
								try {
									/* applications for this particular section */
									$applications = Application::findApplications(null, $section, $professor, $term, PENDING);
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
										} 
										elseif ($data['ratio'] > .66){
											$data['alert'] = 'primary';
										}
										elseif ($data['ratio'] > .33) {
											$data['alert'] = 'warning';
										}
										$positionTotals[$data['title']] = $data;
									}
								}

								$appCount = count($applications);
								if ($appCount == 0) {
									$appCount = '';
								} ?>
											<div class="row">
												<!-- Section description -->
												<div class="col-xs-2">
													<legend><?= ucfirst($section->getSectionType()) ?></legend>	
														
															<?php
																$location = '';
																foreach($sessions as $session){
																	$days = $session->getWeekDays();
																	$time = $session->getStartTime() . "-" . $session->getEndTime();
																	$building = $session->getPlaceBuilding();
																	$room = $session->getPlaceRoom();
																	$location = $building . " " . $room;
																?>
																	<p><small><?= $days . " " . $time ?></small></p>

															<?php

																}
															?>
															<p><small><?= $location ?></small></p> 
														
																									
												</div> <!-- End column -->
												<!-- Applications -->
												<div class="col-xs-8">
														<?php
															if(!$applications){
														?>
															<div class="alert alert-info" role="alert">
																<p>There are currently no available applications for this section.</p>
															</div>
															
														<?php
															}else{																

														?>

												<table class="table table-striped">
													<thead>
														<tr>
															<th>Name</th>
															<th>University ID</th>
															<th>Type</th>
															<th>Application</th>
															<th>Reviews</th>
														</tr>
													</thead>
													<?php

													/* Insert each application */
													foreach($applications as $application){
														$student = $application->getCreator();
														$position = $application->getPosition();
														$profileID = "myProfile" . $student->getID();

													?>
													<tr>
														<?php
															if($positionTotals[$position->getTypeTitle()]['ratio'] == 1){
														?>
														<td>
															<?= $student->getFirstName() . " " . $student->getLastName()?>
														</td>
														<?php		
															}else{
														?>
														<td>
															<div class="dropdown actions">
																<a class="dropdown-toggle" type="button" id="actionsMenu" data-toggle="dropdown">
																<?= $student->getFirstName() . " " . $student->getLastName()?>
																<span class="caret"></span>
																</a>
																<ul class="dropdown-menu" role="menu" id="actionsMenu" aria-labelledby="actionsMenu" data-applicationID="<?= $application->getID() ?>">
																	<li class="decision" role="presentation" data-decision="<?= APPROVED ?>"><a role="menuitem" tabindex="-1">Approve</a></li>
																	<li class="decision" role="presentation" data-decision="<?= REJECTED ?>"><a role="menuitem" tabindex="-1">Reject</a></li>
																	<li class="decision" role="presentation" data-decision="<?= PENDING ?>"><a role="menuitem" tabindex="-1">Postpone</a></li>
																</ul>
															</div>
														</td>
														<?php		
															}
														?>
														<td>
															<?= $student->getUniversityID() ?>
														</td> 
														<td>
															<?= $position->getTypeTitle() ?>
														</td>
														<td>
															<button data-toggle="modal" data-target="#profile-modal" data-appID="<?= $application->getID() ?>" data-usertype="<?= STUDENT ?>" data-userid="<?= $student->getID() ?>" class="btn btn-info circle profile">
																<span class="glyphicon glyphicon-file"></span>
															</button>
														</td>
														<td>
															<button data-toggle="modal" data-target="#commentsModal" data-userID="<?= $student->getID() ?>" class="btn btn-info comments">
																<span class="glyphicon glyphicon-comment"></span>
															</button>
														</td>
													</tr> 												
														
														<?php
														/* Table entry closing brace */
														}

														?>
												</table> <!-- End table -->														
														<?php

															}
														?>

												</div> <!-- End column -->
												<!-- Progress --> 
												<div class="col-xs-2">
													<div class="footer">
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
																<?php
																	if($totalBar['current']){
																	?>
																		<strong>
																			<?=$totalBar['title']?>s
																			<?php
																				if($totalBar['ratio'] == 1){
																			?>
																				<span class="glyphicon glyphicon-ok"></span>
																			<?php		
																				}
																			?>
																		</strong> 
																		<div class="progress progress-striped active">
																			<div class="progress-bar progress-bar-<?= $totalBar['alert'] ?>"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?= $totalBar['ratio'] * 100 ?>%">
																				<?= $totalBar['current']."/".$totalBar['total'] ?> 
																			</div> <!-- End progress-bar progress-bar-danger -->
																			</div> <!-- End progress-bar -->	
																<?php																				
																	}else{

																?>
																		<div class="alert alert-info" role="alert">
																			All <strong><?= $totalBar['title']?></strong> Positions are unfilled!
																		</div>
																<?php
																	}
																?>											
															</div> <!-- End column -->
						<?php
											}
						?>
														</div> <!-- End row -->
													</div> <!-- End panel-footer -->																								
												</div> <!-- End column -->
											</div> <!-- End row -->
											<hr>
						<?php
							}
						?>
									
								</div> <!-- End panel-body -->
							</div> <!-- End panel panel-collapse -->
							<div class="panel-footer">
							</div> <!-- End panel-footer -->
						</div> <!-- End panel panel-primary -->

				<?php	

					}
				}
				?>
			</div> <!-- End panel container -->
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
	
