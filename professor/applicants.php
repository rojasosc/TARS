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
		
		<title>My Applicants</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="applicants.js"></script>
		
	</head>
  
	<body>
	
		
	
  <?php
	$profilesMade = array();
	
	foreach($courses as $course){
	
		$courseID = $course[0];
		$applications = getApplicationsByCourseID($email,$courseID);
		
		foreach($applications as $application){
			
			/*Get studentID */
			$studentID = $application[0];
			
			/*Get profile array representation */
			$student = getStudent($application[3]);
			
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
				<h4 class="modal-title" id="myProfileLabel"> <?= $application[1]?>'s Profile</h4>
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
									<li><a href="assistants.php"><span class="glyphicon glyphicon-th-list"></span> Assistants</a></li>
									<li class="active"><a href="applicants.php"><span class="glyphicon glyphicon-inbox"></span> Applicants</a></li>
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
				Pack these apps into a panel... repeat.
				*/
				$tableEntry = 0;
				foreach($courses as $course){
							
				$courseID = $course[0];
				$courseTitle = $course[2];

				/* applications for this particular course */
				$applications = getApplicationsByCourseID($email,$courseID);
				$totalCoursePositions = countTotalPositions($email,$courseID); /* positions to be filled */
				$filledPositions = count(getFilledPositionsForCourse($email,$courseID)); /* positions that have been filled */					
				
				/* For use in the progress bar */
				$ratio = $filledPositions/$totalCoursePositions;
				$percentage = $ratio * 100;
				
				if($ratio > .66){
				
					$progress = "sucess";
				
				}elseif($ratio > .33){
				
					$progress = "warning";
				}else{
				
					$progress = "danger";
				}
				
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
												<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th>Position ID</th> <th>GPA</th><th>View Profile</th><th>Action</th></tr>
											<?php
											
											/* Insert each application */
											foreach($applications as $application){
												
												$buttonGroupName = "action" . $tableEntry;
												$profileID = "myProfile" . $application[0];
												
											?>
											
											<tr><td><?= $application[0] ?></td> <td><?= $application[1] ?></td> <td><?= $application[2] ?></td> <td><?= $application[3] ?></td><td><?= $application[4] ?></td><td><?= $application[5] ?></td><td><?= $application[6] ?></td><td><a type="button" type="button" data-toggle="modal" href="#<?=$profileID?>" class="btn btn-default">
											<span class="glyphicon glyphicon-user"></span> Profile</a>
											</td>
											<td>
												<div class="btn-group" data-toggle="buttons">
													<label name="lectureSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Approve">
													<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="app<?= $application[0] ?>" value="1 <?= $application[0] ?> <?= $application[5] ?>"><span class="glyphicon glyphicon-ok"></span>
													</label>
													<label name="lectureSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Reject">
													<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="rej<?= $application[0] ?>" value="2 <?= $application[0] ?> <?= $application[5] ?>"><span class="glyphicon glyphicon-remove"></span>
													</label>
													<label name="lectureSelections" class="btn btn-default active" data-toggle="tooltip" data-placement="bottom" title="Undecided">
													<input type="radio" checked="true" name="<?= $buttonGroupName ?>" id="app<?= $application[0] ?>" value="0 <?= $application[0] ?> <?= $application[5] ?>"><span class="glyphicon glyphicon-time"></span>												
													</label>
												</div> <!-- End btn-group -->
											</td>
											</tr> 											
											
											<?php
											/* Table entry closing brace */
											}
											
											?>
											</table> <!-- End table -->
										</form> <!-- End form -->
									</div> <!-- End panel-body -->									
								</div> <!-- End collapse panel-collapse -->
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-3">
											<button name="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok-circle"></span> Confirm</button>
										</div> <!-- End column -->
										</form>									
									</div> <!-- End row -->
									<strong>Positions Filled</strong>
									<div class="progress progress-striped active">
										<div class="progress-bar progress-bar-<?= $progress ?>"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percentage ?>%">
										<?= $filledPositions?>/<?=$totalCoursePositions ?> Positions Filled.
										</div> <!-- End progress-bar progress-bar-danger -->
									</div> <!-- End progress-bar -->
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
	