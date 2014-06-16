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
	</head>
	<body>
	<!-- BEGIN Comment Modal-->
	<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h1 class="modal-title">Student Comment</h1>
				</div> <!-- End modal-header -->
				<div class="modal-body">
					<form action="newComment.php" method="post" id="commentForm" class="form-horizontal">
						<fieldset>
							<legend>New Comment</legend>
							<div class="row">
								<div class="col-xs-10">
									<div class="form-group">
										<input type="text" name="subject" class="form-control" placeholder="Subject">
									</div> <!-- End form-group -->
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group">
										<textarea name="commentText" class="form-control"></textarea>
									</div> <!-- End form-group -->
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $nameBrand ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="staff.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li class="dropdown">
										<a href="manageTerms.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Terms<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="newTerm.php">New Term</a></li>
											<li><a href="editTerm.php">Edit Term</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageProfessors.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Professors<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="createProfessor.php">New Account</a></li>
											<li><a href="editProfessor.php">Edit Account</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown active">
										<a href="manageStudents.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Students<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="editStudent.php">Edit Account</a></li>
											<li class="active"><a href="reviewStudents.php">Review Students</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
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
												
												$applicants = getUnverifiedStudents();
												$tableEntry = 0;
												if($applicants != NULL) {
												foreach($applicants as $applicant){
													$student = $applicant->getStudent();
													$buttonGroupName = "action" . $tableEntry;
													$myProfileID = "myProfile". $student->getID();
												
												?>
												
												<tr>
												<td><?= $student->getUniversityID() ?></td>
												<td><?= $student->getFirstName()?></td>
												<td><?= $student->getLastName() ?></td>
												<td><?= $student->getEmail() ?></td>
												<td><?= $student->getGPA()?></td>
												<td>
												<a type="button" data-toggle="modal" href="#<?= $myProfileID?>" class="btn btn-default">
												<span class="glyphicon glyphicon-user circle"></span></a>			
												</td>
												<td>Status</td>
												<td>
												<button data-toggle="modal" data-target="#commentModal" data-staffID="<?= $staff->getID() ?>" data-studentID="<?= $student->getID() ?>" class="btn btn-default comment">
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
						</div> <!-- End panel panel-primary -->
					</div> <!-- End container -->	
				</div> <!-- End row -->	

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
