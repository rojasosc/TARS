<?php  
    include('staffSession.php');
?>

<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Student Verification</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="comments.js"></script>
		<script src="profiles.js"></script>
		<script src="reviewStudents.js"></script>
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
			
		<!-- BEGIN Comment Modal-->
		<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Student Comment</h1>
					</div> <!-- End modal-header -->
					<div class="modal-body">
						<form action="staffCommands.php" method="post" id="commentForm" class="form-horizontal">
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $staff->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="staff.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li class="dropdown active">
										<a class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage<b class="caret"></b></a>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
											<li role="presentation" class="dropdown-header">Terms</li>
												<li><a href="newTerm.php">New Term</a></li>
												<li><a href="editTerm.php">Edit Term</a></li>
											<li role="presentation" class="divider"></li>
											<li role="presentation" class="dropdown-header">Professors</li>
												<li><a href="createProfessor.php">New Account</a></li>
												<li><a href="editProfessor.php">Edit Account</a></li>											
											<li role="presentation" class="divider"></li>
											<li role="presentation" class="dropdown-header">Students</li>
												<li><a href="reviewStudents.php">Review Students</a></li>	
												<li><a href="editStudent.php">Edit Account</a></li>																				  
										</ul>
									</li> <!-- End dropdown list item -->
									<li><a href="payroll.php"><span class="glyphicon glyphicon-usd"></span> Payroll</a></li>
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
				<!-- Course Panels -->	
				<div class="row">
					<div class="container">					
						<div class="panel panel-primary">
							<div class="panel-heading">
								<p class="panelHeader">Students</p>
							</div> <!-- End panel-heading -->
									<div class="panel-body">
											<table class="table table-striped table-hover">
												<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>GPA</th><th>Profile</th><th>Status</th><th>Comment</th></tr>
												<?php
												
												$studentsToReview = Student::getStudentsToReview();
												$tableEntry = 0;
												if($studentsToReview != NULL) {
												foreach($studentsToReview as $student){

												?>
												
												<tr>
												<td><?= $student->getUniversityID() ?></td>
												<td><?= $student->getFirstName()?></td>
												<td><?= $student->getLastName() ?></td>
												<td><?= $student->getEmail() ?></td>
												<td><?= $student->getGPA()?></td>
												<td>
												<button data-toggle="modal" data-target="#studentProfileModal" data-userID="<?= $student->getID() ?>" class="btn btn-default circle profile">
												<span class="glyphicon glyphicon-user"></span></button>			
												</td>
												<td>	<form>
													<div class="btn-group" data-toggle="buttons" data-userID="<?= $student->getID() ?>">
														<label name="selection" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Verify">
															<input type="radio" name="<?= $student->getID() ?>" id="approve" value="<?= STAFF_VERIFIED ?>" checked><span class="glyphicon glyphicon-ok"></span>
														</label>
														<label name="selection" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Deny">
															<input type="radio" name="<?= $student->getID() ?>" id="deny" value="<?= PENDING ?>" checked><span class="glyphicon glyphicon-remove"></span>
														</label>
														<label name="selection" class="btn btn-info active" data-toggle="tooltip" data-placement="bottom" title="Postpone">
															<input type="radio" name="<?= $student->getID() ?>" id="postpone" value="0" checked><span class="glyphicon glyphicon-time" ></span>												
														</label>
													</div> <!-- End btn-group -->
													</form>
												</td>
												<td>
												<button data-toggle="modal" data-target="#commentModal" data-commenterID="<?= $staff->getID() ?>" data-studentID="<?= $student->getID() ?>" class="btn btn-default comment">
												<span class="glyphicon glyphicon-comment"></span></button>
												</td>
												</tr>							
												<?php
												$tableEntry++;
												}
											}
												?>
												
											</table> <!-- End table -->
									</div> <!-- End panel-body -->
										<div class="panel-footer">
											<div class="row">
												<div class="col-xs-4">
													<button name="applyDecision" id="applyDecisions" class="btn btn-success decisions"><span class="glyphicon glyphicon-ok-circle"></span> Submit Decisions</button>												
												</div> <!-- End column -->
											</div> <!-- End row -->
										</div> <!-- End panel-footer -->									
						</div> <!-- End panel panel-primary -->
					</div> <!-- End container -->	
				</div> <!-- End row -->	

				<!-- END Course Panels -->
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
