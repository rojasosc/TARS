<?php

require_once '../db.php';

$error = null;
$professor = null;
try {
	$professor = LoginSession::sessionContinue(PROFESSOR);
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
		<title>My Assistants</title>		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="professor.css" rel="stylesheet"/>
		<link href="../favicon.ico" rel="shortcut icon"/>

		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/comments.js"></script>
		<script src="../js/tars_utilities.js"></script>
	</head> 
	<body>
		<!-- Profile Modal -->
		<div class="modal fade profile-modal" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="studentProfileModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="studentModalTitle"></h4>
					</div>
					<div class="modal-body">			
						<div id="profileAlertHolder"></div>
						<h3>Personal Information</h3>
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
		<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Student Review</h1>
						<small>Please, briefly describe this student's performance.</small>
						<small> For example, describe this assistant's productivity, enthusiasm, punctuality, initiative, or dependability.</small>
					</div> <!-- End modal-header -->
					<div class="modal-body">
						<div id="createCommentAlertHolder"></div>
						<form action="professorCommands.php" method="post" id="commentForm" class="form-horizontal">
							<fieldset>
								<div class="row">
									<div class="col-xs-12">
										<textarea name="commentText" class="form-control"></textarea>
									</div> <!-- End column -->
								</div> <!-- End row -->													
							</fieldset> <!-- End comment fieldset -->
						</form> <!-- End comment form -->
					</div> <!-- End modal-body -->
					<div class="modal-footer">
						<button class="btn btn-danger" data-dismiss="modal">Close</button>
						<button class="btn btn-primary" name="submitComment" id="submitCommentButton">Add Comment</button>
					</div> <!-- End modal-footer -->				
				</div> <!-- End modal-content -->
			</div> <!-- End modal-dialog -->
		</div> <!-- End modal fade -->	
		<!-- END Comment Modal-->

		 <!-- BEGIN Reviews Modal-->
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
		<!-- END Reviews Modal-->		
			
			<!-- Begin Email Modal -->
		<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myProfileLabel">TARS</h4>
					</div> <!-- End modal-header -->
					<div class="modal-body">	
						<h3>Email Form</h3>
						<div class="container">			
							<form action="#" method="post">			
								<fieldset>				
									<div class="row">					
										<div class="col-sm-8 col-md-6">
											<label>Subject</label>
											<input type="text" name="subjectLine" class="form-control" placeholder="Enter A Subject Line.">
										</div> <!-- End column -->
									</div><!-- End row -->					
									<div class="row">					
										<div class="col-sm-8 col-md-6">
											<label>Message</label>
											<textarea class="form-control" rows="3" placeholder="Enter Your Message."></textarea> <!-- End text area -->
										</div> <!-- End column -->
									</div> <!-- End row -->					
								</fieldset>				
							</form> <!-- End form -->
						</div> <!-- End container -->
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-danger">Close</button> <!-- close modal -->
					</div>	<!-- End modal footer -->
					</div> <!-- End modal body -->
				</div> <!-- End modal-content -->
			</div> <!--End modal-dialog-->
		</div> <!-- End modal fade -->  
		 <!-- End Email Modal -->

		<!-- BEGIN page-wrapper -->
		<div id="page-wrapper">

<?php
// Display header for Assistants
$header_active = 'asst';
require 'header.php';
?>
			<!-- BEGIN page content -->
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
					<h1 class="panelHeader">My Assistants</h1>
				</div> <!-- End row -->			
				<div class="container">
				<?php
					foreach($courses as $course) {
						$sections = $professor->getSectionsByCourseID($course['courseID']);
						$applications = Application::getAssistantsByCourseID($course['courseID'],$professor);
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
									$applications = Application::findApplications(null, $section, $professor, $term, APPROVED);
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
												<div class="col-xs-10">
														<?php
															if(!$applications){
														?>
															<div class="alert alert-info" role="alert">
																<p>You have not yet approved any applications for this section.</p>
															</div>
															
														<?php
															}else{																

														?>

												<table class="table table-striped">
													<thead>
														<tr>
															<th>Name</th>
															<th>University ID</th>
															<th>Email</th>
															<th>Type</th>
															<th>Profile</th>
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
														<td>
															<div class="dropdown actions">
																<a class="dropdown-toggle" type="button" id="actionsMenu" data-toggle="dropdown">
																<?= $student->getFirstName() . " " . $student->getLastName()?>
																<span class="caret"></span>
																</a>
																<ul class="dropdown-menu" role="menu" id="actionsMenu" aria-labelledby="actionsMenu">
																	<li role="presentation"><a class="comment" role="menuitem" data-commenterID="<?= $professor->getID() ?>" data-studentID="<?= $student->getID() ?>" data-toggle="modal" href="#commentModal" tabindex="-1">Review Student</a></li>
																	<li role="presentation"><a data-toggle="modal" role="menuitem" tabindex="-1" data-target="#emailModal">Send Email</a></li>
																</ul>
															</div>
														</td>
														<td>
															<?= $student->getUniversityID() ?>
														</td>
														<td>
															<?= $student->getEmail() ?>
														</td> 
														<td>
															<?= $position->getTypeTitle() ?>
														</td>
														<td>
															<button data-toggle="modal" data-target="#profile-modal" data-appID="<?= $application->getID() ?>" data-usertype="<?= STUDENT ?>" data-userid="<?= $student->getID() ?>" class="btn btn-info circle profile">
																<span class="glyphicon glyphicon-user"></span>
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
	
