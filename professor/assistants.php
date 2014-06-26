<?php
	include('professorSession.php');	
	$courses = $professor->getCourses();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<title>My Assistants</title>		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="applicants.js"></script>
		<script src="comments.js"></script>
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
						
						<h3>About Me</h3>
						<div class="container">
							<p id="studentAboutMe"></p>	
						</div> <!-- End container -->							
					</div> <!-- End modal body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div> <!-- End modal-footer -->
				</div> <!-- End modal-content -->
			</div> <!-- End modal-dialog -->
		</div> <!-- End modal fade -->
		<!-- End Profile Modal -->
			
			<!-- Begin Email Modal -->
		<div class="modal fade" id="emailTAs" tabindex="-1" role="dialog" aria-labelledby="emailTAsLabel" aria-hidden="true">
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
										<div class="col-md-6">
											<label>Subject</label>
											<input type="text" name="subjectLine" class="form-control" placeholder="Enter A Subject Line.">
										</div> <!-- End column -->
									</div><!-- End row -->					
									<div class="row">					
										<div class="col-md-6">
											<label>Message</label>
											<textarea class="form-control" rows="3" placeholder="Enter Your Message.">
											</textarea> <!-- End text area -->
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

		 <!-- BEGIN Comment Modal-->
		<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Student Comment</h1>
					</div> <!-- End modal-header -->
					<div class="modal-body">
						<form action="professorCommands.php" method="post" id="commentForm" class="form-horizontal">
							<fieldset>
								<legend>Message Content</legend>
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

		<!-- BEGIN page-wrapper -->
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
								<a class="navbar-brand" href="editProfile.php"><span class="glyphicon glyphicon-user"></span> <?= $nameBrand ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="professor.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li class="active"><a href="assistants.php"><span class="glyphicon glyphicon-th-list"></span> Assistants</a></li>
									<li><a href="applicants.php"><span class="glyphicon glyphicon-inbox"></span> Applicants</a></li>
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
					<h1 class="panelHeader">My Assistants</h1>
				</div> <!-- End row -->			
							
				<!-- Course Panels -->
				<?php
				/*Obtain positionIDS that are in the Assistantship table
				and that match a particular CRN	
				Pack these assistants into a panel... repeat.
				*/
				$tableEntry = 0;
				$term = Term::getTermByID(CURRENT_TERM);
				foreach($courses as $course){
				
				/* assistants for this particular course */
				$assistants = Application::getApplications($course, $professor, $term, APPROVED);
								
				/* create a new panel */ 
				$panelID = "coursePanel" . $course->getID();

				$coursePanelName = $course->getTitle();
				?>
				
				<div class="row">
					<div class="container">					
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panelHeader" data-toggle="collapse" data-target="#<?= $panelID ?>"><?= $coursePanelName ?></h4>
							</div> <!-- End panel-heading -->
								<div class="collapse panel-collapse" id="<?= $panelID ?>">
									<div class="panel-body">
											<table class="table table-striped">
												<thead>
													<tr>
															<th>ID</th>
															<th>First Name</th>
															<th>Last Name</th>
															<th>Email</th>
															<th>Type</th>
															<th>View Profile</th>
															<th>Comment</th>
													</tr>
												</thead>
											<?php
											
											/* Insert each application */
											foreach($assistants as $assistant){
												$student = $assistant->getStudent();
												$position = $assistant->getPosition();
												$profileID = "myProfile" . $student->getID();
												
											?>
											
											<tr><td><?= $student->getID() ?></td> <td><?= $student->getFirstName() ?></td> <td><?= $student->getLastName() ?></td><td><?= $student->getEmail() ?></td><td><?= $position->getPositionType() ?></td>
												<td><button data-toggle="modal" data-target="#studentProfileModal" data-id="<?= $student->getID() ?>" class="btn btn-default circle profile">
												<span class="glyphicon glyphicon-user"></span></button>
												</td>
												<td>
													<button data-toggle="modal" data-target="#commentModal" data-commenterID="<?= $professor->getID() ?>" data-studentID="<?= $student->getID() ?>" class="btn btn-default comment">
													<span class="glyphicon glyphicon-comment"></span></button>
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
							<div class="panel-footer"><a type="button" type="button" data-toggle="modal" href="#emailTAs" class="btn btn-default">
								<span class="glyphicon glyphicon-envelope"></span> Email</a>
							</div> <!-- End panel-footer -->								
						</div> <!-- End panel panel-primary -->
					</div> <!-- End container -->	
				</div> <!-- End row -->
				
				<?php
				
				/* Course panels closing brace */
				}
				
				?>		

				<!-- END Course Panels -->
			
			</div>
			<!-- END Page Content --> 
	    
			<!--BEGIN Page Footer -->
			<div id="footer">
				<div class="container">
					<div class="row">
						<div class="col-xs-4">						
							<ul id="contact-us">
								<lh>Contact Us</lh>
								<li> <br />
									Oscar Rojas <br />
									Email: orojas@u.rochester.edu <br />
									Phone Number: 404-996-7988<br />
								</li>
								<li> <br />
									Jinze An <br />
									Email: jan2@u.rochester.edu <br />
									Phone Number: 585-749-5590 <br />
								</li>
							</ul>
						</div>
					</div>
				</div> <!-- End row -->
			</div>
			<!--END Page Footer -->
	
		</div> 
		<!-- End page-wrapper -->
	</body>
</html>
	
