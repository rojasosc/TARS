<?php
<<<<<<< HEAD
	include('professorSession.php');
	/* Obtain courses */
	$courses = $professor->getCourses();	
=======
//ini_set("display_errors",1);
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
$courses = $professor->getCourses();
>>>>>>> origin/stage
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
		<script src="comments.js"></script>
		<script src="../js/tars_utilities.js"></script>
	</head> 
	<body>
		<!-- Profile Modal -->
<<<<<<< HEAD
		<div class="modal fade" id="studentProfileModal" tabindex="-1" role="dialog" aria-labelledby="studentProfileModal" aria-hidden="true">
=======
		<div class="modal fade profile-modal" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="studentProfileModal" aria-hidden="true">
>>>>>>> origin/stage
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<<<<<<< HEAD
						<h4 class="modal-title" id="studentModalTitle"></h4>
					</div>
					<div class="modal-body">			
						<h3>Personal Information</h3>
=======
						<h2 class="modal-title" id="studentModalTitle"></h2>
					</div>
					<div class="modal-body">
						<h3>Qualifications</h3>
						<div class="container qualifications">
						</div> <!-- End container -->									
						<h3>Academic Information</h3>
>>>>>>> origin/stage
						<div class="container">
							<p id="studentMajor"></p>
							<p id="studentGPA"></p>
							<p id="studentClassYear"></p>
<<<<<<< HEAD
						</div> <!-- End container -->			
=======
						</div> <!-- End container -->								
>>>>>>> origin/stage
						<h3>Contact Information</h3>
						<div class="container">
							<p id="studentEmail"></p>
							<p id="studentMobilePhone"></p>
						</div> <!-- End container -->
<<<<<<< HEAD
						<h3>Staff Comments</h3>
						<div class="container">
							<div class="row">
								<div class="col-xs-4">
									<p>Commenter:</p>
								</div> <!-- End column -->
								<div class="col-xs-4">
									<p>Date:</p>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-xs-12">
									<p>Message:</p>
								</div> <!-- End column -->
							</div> <!-- End row -->
						</div> <!-- End container -->
						<h3>Professor Comments</h3>
						<div class="container">
							<div class="row">
								<div class="col-xs-4">
									<p>Commenter:</p>
								</div> <!-- End column -->
								<div class="col-xs-4">
									<p>Date:</p>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-xs-12">
									<p>Message:</p>
								</div> <!-- End column -->
							</div> <!-- End row -->
						</div> <!-- End container -->
=======
>>>>>>> origin/stage
					</div> <!-- End modal body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div> <!-- End modal-footer -->
				</div> <!-- End modal-content -->
			</div> <!-- End modal-dialog -->
		</div> <!-- End modal fade -->
		<!-- End Profile Modal -->
<<<<<<< HEAD
		
=======

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
>>>>>>> origin/stage
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
<<<<<<< HEAD
								
								$totalGraders = $course->getTotalPositionsByType($professor, "Grader");
								$currentGraders = $course->getCurrentPositionsByType($professor, "Grader");
								$totalLabTAs = $course->getTotalPositionsByType($professor, "Lab TA");
								$currentLabTAs = $course->getCurrentPositionsByType($professor, "Lab TA");
								$totalWorkshopLeaders = $course->getTotalPositionsByType($professor, "Workshop Leader");
								$currentWorkshopLeaders = $course->getCurrentPositionsByType($professor, "Workshop Leader");
								
								/*TODO: Mark workshop super leader positions in the db so that we 
								 can highlight them on professor pages. */
								 
								 /* Determine the color of the progress bars based on current/total ratio */
								include('progressBars.php');
						?>				
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h4 class="panelHeader" data-toggle="collapse" data-target="#coursePanel<?=$courseCRN?>"><?= $courseTitle ?></h4>
								</div> <!-- End panel-heading -->
									<div class="collapse panel-collapse" id="coursePanel<?=$courseCRN?>">
										<div class="panel-body">
										<!--TODO: Find a user-friedly way to display 
										applicants using AJAX to prevent workflow errors
										(e.g. accepting two applicants into the same position)
										. Idea: color code rows, maybe? Update all submit requests
										to use AJAX.
										 -->
											<table class="table table-striped" id="<?= $courseCRN ?>">
												<tr><th class="hidden-xs hidden-sm">University ID</th><th>First Name</th><th>Last Name</th><th class="hidden-xs">Email</th><th>Type</th><th>GPA</th><th>Profile</th><th>Action</th></tr>
												<?php
													foreach($applications as $application){ 
														$student = $application->getStudent();
														$applicationID = $application->getID();
														$universityID = $student->getUniversityID();
=======

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
>>>>>>> origin/stage
														
															<?php
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
														
<<<<<<< HEAD
												?>
												
												<tr>
												<td class="hidden-xs hidden-sm"><?= $universityID ?></td> 
												<td><?= $student->getFirstName() ?></td>
												<td><?= $student->getlastName() ?></td>
												<td class="hidden-xs"><?= $student->getEmail() ?></td>
												<td><?= $application->getPosition()->getPositionType() ?></td>
												<td><?= $student->getGPA()?></td>
												<td><button data-toggle="modal" data-target="#studentProfileModal" data-id="<?= $student->getID() ?>" class="btn btn-default circle profile">
												<span class="glyphicon glyphicon-user"></span></button>
												</td>
												<td>	<form id="course1">
													<div class="btn-group" data-toggle="buttons" data-positionID="<?= $application->getPosition()->getID() ?>" data-universityID="<?= $student->getID() ?>">
														<label name="selection" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Approve">
															<input type="radio" name="<?= $applicationID ?>" id="approve" value="<?= APPROVED ?>" checked><span class="glyphicon glyphicon-ok"></span>
														</label>
														<label name="selection" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Deny">
															<input type="radio" name="<?= $applicationID ?>" id="deny" value="<?= REJECTED ?>" checked><span class="glyphicon glyphicon-remove"></span>
														</label>
														<label name="selection" class="btn btn-info active" data-toggle="tooltip" data-placement="bottom" title="Postpone">
															<input type="radio" name="<?= $applicationID ?>" id="postpone" value="0" checked><span class="glyphicon glyphicon-time" ></span>												
														</label>
													</div> <!-- End btn-group -->
													</form>
												</td>
												</tr> 													
												<?php	} ?>	<!-- Finished looping through every application -->											
											</table> <!-- End table table-striped -->
										 
										</div> <!-- End panel-body -->										
									</div> <!-- End collapse panel-collapse -->
										<div class="panel-footer">
											<div class="row">
												<div class="col-xs-4">
													<strong>Graders</strong>
													<div class="progress progress-striped active">
														<div class="progress-bar progress-bar-<?= $graderBar ?>"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?= $graderRatio * 100 ?>%">
														</div> <!-- End progress-bar progress-bar-danger -->
													</div> <!-- End progress-bar -->												
=======
																									
>>>>>>> origin/stage
												</div> <!-- End column -->
												<!-- Applications -->
												<div class="col-xs-8">
														<?php
															if(!$applications){
														?>
															<div class="alert alert-danger" role="alert">
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
														$tableEntry++;
														}

														?>
												</table> <!-- End table -->														
														<?php

															}
														?>

												</div> <!-- End column -->
<<<<<<< HEAD
												<div class="col-xs-4">
													<strong>Workshop Leaders</strong>
													<div class="progress progress-striped active">
														<div class="progress-bar progress-bar-<?= $workshopLeaderBar ?>"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?= $workShopLeaderRatio * 100 ?>%">
														</div> <!-- End progress-bar progress-bar-danger -->
													</div> <!-- End progress-bar -->												
												</div> <!-- End column -->											
											</div> <!-- End row -->
											<div class="row">
												<div class="col-xs-4">
													<button name="applyDecision" data-courseID="<?= $courseCRN ?>" class="btn btn-success decisions"><span class="glyphicon glyphicon-ok-circle"></span> Submit Decisions</button>												
=======
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
																		<div class="alert alert-danger" role="alert">
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
>>>>>>> origin/stage
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
	
