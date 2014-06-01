<?php
	include('professorSession.php');

	/* Obtain a CRN and a courseNumber */
	
	$courses = getCourses($email);

?>
<!-- A template for TARS.

This template consists of a wrapper div tag that encloses
a set of header, content, and footer div tags.

There are three ids inside the css file that provide the 
necessary styling for the three components. 

Using this structure we can fix the footer at the bottom and 
maintain a solid structure through scrolling.

The images are background images and not img tags. 

The navbar is collapsable and seems to work pretty well. However,
the navbar-brand does seem to run out of space if the window is shrunk enough. 

-->
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
	</head> 
	<body>
  <?php
	$profilesMade = array();
	
	foreach($courses as $course){
	
		$courseID = $course[0];
		$assistants = getApplicationsByCourseID($email,$courseID);
		
		foreach($assistants as $assistant){
			
			/*Get studentID */
			$studentID = $assistant[0];
			
			/*Get profile array representation */
			$student = getStudent($assistant[3]);
			
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
				<h4 class="modal-title" id="myProfileLabel"> <?= $assistant[1]?>'s Profile</h4>
			</div>
			<div class="modal-body">
			
				<h3>Personal Information</h3>
				<div class="container">
				<p>Major: <?=$student['major']?></p>
				<p>GPA: <?=$student['gpa']?></p>
				<p>Class Year: <?=$student['classYear']?></p>
				</div>
				
				<h3>Contact Information</h3>
				<div class="container">
				<p>Email: email </p>
				<p>Mobile Phone: <?=$student['homePhone']?> </p>
				<p>Home Phone: <?=$student['mobilePhone']?> </p>
				</div>
				
				<h3>About Me</h3>
				<div class="container">
				<p><?=$student['aboutMe']?></p>
				
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
											<li><a href="#">Lecture Assistants</a></li>
											<li><a href="#">Lab Assistants</a></li>
											<li><a href="#">Workshop Assistants</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
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
				<?php
				/*Obtain positionIDS that are in the Assistantship table
				and that match a particular CRN	
				Pack these assistants into a panel... repeat.
				*/
				$tableEntry = 0;
				foreach($courses as $course){
							
				$courseID = $course[0];
				$courseTitle = $course[2];

				/* assistants for this particular course */
				$assistants = getFilledPositionsForCourse($email,$courseID);
								
				/* create a new panel */ 
				$panelID = "coursePanel" . $courseID;

				$coursePanelName = $courseTitle . " " . $couseNumber . "\tCRN: " . $courseID;
				
				?>
				
				<div class="row">
					<div class="container">					
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 data-toggle="collapse" data-target="#<?= $panelID ?>"><?= $coursePanelName ?></h4>
							</div> <!-- End panel-heading -->
								<div class="collapse panel-collapse" id="<?= $panelID ?>">
									<div class="panel-body">
										<form action="selections.php" method="post" id="formid">
											<table class="table table-striped">
												<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>View Profile</th></tr>
											<?php
											
											/* Insert each application */
											foreach($assistants as $assistant){
												
												$buttonGroupName = "action" . $tableEntry;
												$profileID = "myProfile" . $assistant[0];
												
											?>
											
											<tr><td><?= $assistant[0] ?></td> <td><?= $assistant[1] ?></td> <td><?= $assistant[2] ?></td><td><?= $assistant[3] ?></td><td><a type="button" type="button" data-toggle="modal" href="#<?=$myProfileID?>" class="btn btn-default">
											<span class="glyphicon glyphicon-user"></span> Profile</a>
											</tr> 											
											
											<?php
											/* Table entry closing brace */
											}
											
											?>
											</table> <!-- End table -->
										</form> <!-- End form -->
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
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<!-- END Scripts -->
	</body>
</html>
	