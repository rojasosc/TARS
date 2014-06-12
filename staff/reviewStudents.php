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
	</head>
	<body>
	
	
  <?php
		$profilesMade = array();
		$applicants = getUnverifiedStudents();
		$totalUnverified = count($applicants);
		$totalStudents = totalAssistantCount();
		
		$totalVerified = $totalStudents - $totalUnverified;
		
		
		/* For use in the progress bar */
		$ratio = $totalVerified/$totalStudents;
		$percentage = $ratio * 100;
				
		if($ratio > .66){
				
			$progress = "success";
				
		}elseif($ratio > .33){
				
			$progress = "warning";
		}else{
				
			$progress = "danger";
		}		
		
	if($applicants != NULL) {
		foreach($applicants as $applicant){

			$student = $applicant->getStudent();
			
			/*Get studentID */
			$studentID = $student->getID();
			
			if(!in_array($studentID,$profilesMade)){
			
				$myProfileID = "myProfile". $studentID;
				$profilesMade[] = $studentID;
			?>
			
			<!-- Profile Modal -->
			<div class="modal fade" id = "<?=$myProfileID?>" tabindex="-1" role="dialog" aria-labelledby="myProfileLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myProfileLabel"> <?= $student->getFirstName()?>'s Profile</h4>
			</div>
			<div class="modal-body">
			
				<h3>Personal Information</h3>
				<div class="container">
				<p>Major: <?=$student->getMajor()?></p>
				<p>GPA: <?=$student->getGPA()?></p>
				<p>Class Year: <?=$student->getClassYear()?></p>
				</div>
				
				<h3>Contact Information</h3>
				<div class="container">
				<p>Email: <?=$student->getEmail()?> </p>
				<p>Mobile Phone: <?=$student->getMobilePhone()?> </p>
				</div>
				
				<h3>About Me</h3>
				<div class="container">
				<p><?=$student->getAboutMe()?></p>
				
				</div>
				
			</div>
			<div class="modal-footer">
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
			</div>
			</div>
			</div>

			<!-- End Profile Modal -->
			
			<?php
			}
		}	
	
	}
    ?>	
    
		<!-- BEGIN Comment Modal-->
		<div class="modal fade" id="comment" tabindex="-1" role="dialog" aria-labelledby="bugModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Student Comment</h1>
					</div> 
					<div class="modal-body">
						<p>
							Enter a comment for (studentName) in the space provided.<br>
							These comments are made visible to professors and are used to filter applications.
						</p>
						<form action="commentProcess.php" method="post" id="comment">
							<fieldset>
								<div class="row">
									<div class="col-xs-5 col-xs-offset-1">
										<label>
											Subject: <input class="form-control" type="text" name="commentSubject" size="32"/>
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<label>
											<textarea class="form-control" rows="4" cols="64"></textarea>
										</label>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" name="submitComment" id="submitButton">Add Comment</button>
					</div>
				</div>
			</div>
		</div>
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
										<form action="reviewProcess.php" method="post" id="reviewTable">
											<table class="table table-striped">
												<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>GPA</th><th>View Profile</th><th>Status</th><th>Comment</th></tr>
												<?php
												
												$applicants = getUnverifiedStudents();
												$tableEntry = 0;
												if($applicants != NULL) {
												foreach($applicants as $applicant){
													$student = $applicant->getStudent();
													$buttonGroupName = "action" . $tableEntry;
													$myProfileID = "myProfile". $student->getID();
												
												?>
												
												<tr><td><?= $student->getUniversityID() ?></td><td><?= $student->getFirstName()?></td><td><?= $student->getLastName() ?></td><td><?= $student->getEmail() ?></td><td><?= $student->getGPA()?></td>
												<td>
												<a type="button" data-toggle="modal" href="#<?= $myProfileID?>" class="btn btn-default">
												<span class="glyphicon glyphicon-user"></span> Profile</a>			
												</td>
												<td>Status</td>
												<td>
												<a type="button" href="#comment" data-toggle="modal" class="btn btn-default">
												<span class="glyphicon glyphicon-comment"></span> Comment</a>
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
										<div class="col-md-3">
											<button name="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok-circle"></span> Apply Changes</button>
										</div> <!-- End column -->
										</form> <!-- End form -->								
									</div> <!-- End row -->
									<strong>Students Reviewed</strong>
									<div class="progress progress-striped active">
										<div class="progress-bar progress-bar-<?= $progress ?>"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:<?= $percentage?>%">
										<?= $totalVerified?>/<?= $totalStudents?> Students Reviewed
										</div> <!-- End progress-bar progress-bar-danger -->
									</div> <!-- End progress-bar -->
								</div> <!-- End panel-footer -->								
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
