<?php
require_once 'professorSession.php';	

$term = null;
$sections = array();
if ($error != null) {
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
		<title>My Assistants</title>		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="comments.js"></script>
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

<?php
// Display header for Assistants
$header_active = 'asst';
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
					<h1 class="panelHeader">My Assistants</h1>
				</div> <!-- End row -->			
							
				<!-- Course Panels -->
				<?php
				/*Obtain positionIDS that are in the Assistantship table
				and that match a particular CRN	
				Pack these assistants into a panel... repeat.
				 */
				$tableEntry = 0;
				foreach($sections as $section){
					$assistants = array();
					try {
						/* assistants for this particular section */
						$assistants = Application::getApplications($section, $professor, $term, APPROVED);
					} catch (PDOException $ex) {
						$error = new TarsException(Event::SERVER_DBERROR,
							Event::USER_GET_POSITIONS, $ex);
						echo $error->toHTML();
					}

					/* create a new panel */ 
					$panelID = "coursePanel" . $section->getID();

					$coursePanelName = $section->getCourseTitle();
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
															<th>University ID</th>
															<th>First Name</th>
															<th>Last Name</th>
															<th>Email</th>
															<th>Type</th>
															<th>Profile</th>
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
											
											<tr><td><?= $student->getUniversityID() ?></td> <td><?= $student->getFirstName() ?></td> <td><?= $student->getLastName() ?></td><td><?= $student->getEmail() ?></td><td><?= $position->getPositionType() ?></td>
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
				if (count($sections) == 0) {
					echo '<div class="alert alert-info">There are no sections assigned to you this term.</div>';
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
	
