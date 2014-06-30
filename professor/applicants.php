<?php
	include('professorSession.php');
	/* Obtain courses */
	$courses = $professor->getSections();	
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
		<script src="profiles.js"></script>

		
	</head>
	<body>
		<!-- Profile Modal -->
		<div class="modal fade" id="studentProfileModal" tabindex="-1" role="dialog" aria-labelledby="studentProfileModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="studentModalTitle"></h4>
					</div>
					<div class="modal-body">			
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
						<h3>Staff Comments</h3>
						<div class="container staffComments">
						</div> <!-- End container -->
						<h3>Professor Comments</h3>
						<div class="container professorComments">
						</div> <!-- End container -->
					</div> <!-- End modal body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div> <!-- End modal-footer -->
				</div> <!-- End modal-content -->
			</div> <!-- End modal-dialog -->
		</div> <!-- End modal fade -->
		<!-- End Profile Modal -->
		
		<div id="page-wrapper">
			<!-- BEGIN Page Header -->
			<div id="header">
				<div class="row" id="navbar-theme">
					<nav class="navbar navbar-default navbar-static-top" role="navigation">
						<div class="container-fluid">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only">Toggle Navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="editProfile.php"><span class="glyphicon glyphicon-user"></span> <?= $professor->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="professor.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li><a href="assistants.php"><span class="glyphicon glyphicon-th-list"></span> Assistants</a></li>
									<li class="active"><a href="applicants.php"><span class="glyphicon glyphicon-inbox"></span> Applicants</a></li>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">Feedback <b class="caret"></b></a>
										<ul class="dropdown-menu">
										<?php
											/* Create links for each course */
											foreach($courses as $course){
											
											?>
											
											<li data-toggle="tool-tip" title="<?= "CRN: ".$course->getCRN() ?>"><a href="#"><?= $course->getTitle() ?></a></li>
	
										<?php	
											}
										?>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
								</ul> <!-- End navbar unordered list -->								
								<ul class="nav navbar-nav navbar-right">
									<li><a href="../logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
								</ul> <!-- End navbar unordered list -->
								
							</div> <!-- End navbar-collapse collapse -->        
						</div> <!-- End container-fluid -->
					</nav>
				</div> <!-- End navbar-theme -->
			</div>		
			<!--END Page Header -->	  
	  
			<!-- BEGIN Page Content -->
			<div id="content">
				<div class="row">
					<h1 class="panelHeader">My Applicants</h1>
				</div> <!-- End row -->
				<div class="container" id="contentWrapper">
					<!-- Course Panels -->
					<div class="container" id="coursesContainer">
						 <?php
							$term = Term::getTermByID(CURRENT_TERM);
							foreach($courses as $course){	
								$courseCRN = $course->getCRN();
								$courseTitle = $course->getTitle();
								
								$applications = Application::getApplications($course, $professor, $term, PENDING);	/* All applications for this course */
								
								/*TODO: Get total positions of a particular type and course.
								For instance, all the graders for CSC 172.*/
								
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
												</div> <!-- End column -->
												<div class="col-xs-4">
													<strong>Lab TAs</strong>
													<div class="progress progress-striped active">
														<div class="progress-bar progress-bar-<?= $labTAsBar ?>"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?= $labTAsRatio * 100 ?>%">
														</div> <!-- End progress-bar progress-bar-danger -->		
													</div> <!-- End progress-bar -->												
												</div> <!-- End column -->
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
												</div> <!-- End column -->
											</div> <!-- End row -->
										</div> <!-- End panel-footer -->									
							</div> <!-- End panel panel-primary -->
						<?php } ?> <!-- Finished looping through every course -->	
						</div> <!-- End container -->
					<!-- End Course Panels -->			
				</div> <!-- End container content-wrapper-->
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
	
