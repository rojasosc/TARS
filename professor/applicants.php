<?php
  
    include('../dbinterface.php');
  
    session_start();
    
    
    if (!isset($_SESSION['auth'])) {

    // if not redirect to login screen. 
    
    header('Location: ../index.php');


    }else{
  
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName']; 
    
    $firstLetter = $firstName[0]; /* Holds the first letter of the first name. */
    $firstLetter .= ".";
    $name = $firstLetter . " " . $lastName;
    
    $tableEntry = 0; //current table entry. 
    
        //Obtain Applicants IDS
    
    $email = $_SESSION['email'];
    $lectureApps = getApplicants($email,"Lecture TA");
    $currentLectureAssistants = 7;
    $maximumLecturePositions = 10;
    
    $lectureRatio = $currentLectureAssistants/$maximumLecturePositions;
    $lectureBarStatus = "danger";

    if($lectureRatio > .66){
    
	$lectureBarStatus = "success";
    
    }else if($lectureRatio < .33){
	$lectureBarStatus = "danger";
    }else{
	$lectureBarStatus = "warning"; 
    }
    
    $labApps = getApplicants($email,"Lab TA");
    $currentLabAssistants = 5;
    $maximumLabPositions = 10;
    
    $labRatio = $currentLabAssistants/$maximumLabPositions;
    $labBarStatus = "danger";

    if($labRatio > .66){
    
	$labBarStatus = "success";
    
    }else if($labRatio < .33){
	$labBarStatus = "danger";
    }else{
	$labBarStatus = "warning"; 
    }
    
    
    $workshopApps = getApplicants($email,"Workshop Leader");
    $currentWorkshopAssistants = 2;
    $maximumWorkshopPositions = 10;
    
    $workshopRatio = $currentWorkshopAssistants/$maximumWorkshopPositions;
    $workshopBarStatus = "danger";

    if($workshopRatio > .66){
    
	$workshopBarStatus = "success";
    
    }else if($workshopRatio < .33){
	$workshopBarStatus = "danger";
    }else{
	$workshopBarStatus = "warning"; 
    }
  
  }

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
		
	</head>
  
	<body>
	
 <?php  
    foreach($lectureApps as $taProfile){
    
      if(!(in_array($taProfile[0],$profilesMade))){
      
      
      
      $myProfileID = "myProfile" . $taProfile[0];
      $student = getStudentByID($taProfile[0]);
    
      $studentB = getStudentByID2($taProfile[0]);
      
      $profilesMade[] = $taProfile[0];
      
    
      

?>
    

      <!-- Profile Modal -->
<div class="modal fade" id = "<?=$myProfileID?>" tabindex="-1" role="dialog" aria-labelledby="myProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myProfileLabel"> <?= $taProfile[1]?>'s Profile</h4>
      </div>
      <div class="modal-body">
      
	<h3>Personal Information</h3>
	<div class="container">
	<p>Major: <?=$student[major]?></p>
	<p>GPA: <?=$student[GPA]?></p>
	<p>Class Year: <?=$student[classYear]?></p>
	</div>
	
	<h3>Contact Information</h3>
	<div class="container">
	<p>Email: <?=$studentB[email]?></p>
	<p>Mobile Phone: <?=$studentB[phone]?> </p>
	<p>Home Phone: <?=$studentB[homePhone]?> </p>
	</div>
	
	<h3>About Me</h3>
	<div class="container">
	<p><?=$student[about]?></p>
	
	</div>
	
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- End Profile Modal -->
    
    
    
    
    
    <?php } } ?> 
    
   
  <?php  
    foreach($workshopApps as $taProfile){
    
      if(!(in_array($taProfile[0],$profilesMade))){
      
      
      
      $myProfileID = "myProfile" . $taProfile[0];
      $student = getStudentByID($taProfile[0]);
    
      $studentB = getStudentByID2($taProfile[0]);
      
      $profilesMade[] = $taProfile[0];
      
    
      

    ?>
    

      <!-- Profile Modal -->
<div class="modal fade" id = "<?=$myProfileID?>" tabindex="-1" role="dialog" aria-labelledby="myProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myProfileLabel"> <?= $taProfile[1]?>'s Profile</h4>
      </div>
      <div class="modal-body">
      
	<h3>Personal Information</h3>
	<div class="container">
	<p>Major: <?=$student[major]?></p>
	<p>GPA: <?=$student[GPA]?></p>
	<p>Class Year: <?=$student[classYear]?></p>
	</div>
	
	<h3>Contact Information</h3>
	<div class="container">
	<p>Email: <?=$studentB[email]?></p>
	<p>Mobile Phone: <?=$studentB[phone]?> </p>
	<p>Home Phone: <?=$studentB[homePhone]?> </p>
	</div>
	
	<h3>About Me</h3>
	<div class="container">
	<p><?=$student[about]?></p>
	
	</div>
	
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- End Profile Modal -->
    
    
    
    
    
    <?php } } ?> 
 
 
 
 <?php  
    foreach($labApps as $taProfile){
    
      if(!(in_array($taProfile[0],$profilesMade))){
      
      
      
      $myProfileID = "myProfile" . $taProfile[0];
      $student = getStudentByID($taProfile[0]);
    
      $studentB = getStudentByID2($taProfile[0]);
      
      $profilesMade[] = $taProfile[0];
      
    
      

    ?>
    

      <!-- Profile Modal -->
<div class="modal fade" id = "<?=$myProfileID?>" tabindex="-1" role="dialog" aria-labelledby="myProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myProfileLabel"> <?= $taProfile[1]?>'s Profile</h4>
      </div>
      <div class="modal-body">
      
	<h3>Personal Information</h3>
	<div class="container">
	<p>Major: <?=$student[major]?></p>
	<p>GPA: <?=$student[GPA]?></p>
	<p>Class Year: <?=$student[classYear]?></p>
	</div>
	
	<h3>Contact Information</h3>
	<div class="container">
	<p>Email: <?=$studentB[email]?></p>
	<p>Mobile Phone: <?=$studentB[phone]?> </p>
	<p>Home Phone: <?=$studentB[homePhone]?> </p>
	</div>
	
	<h3>About Me</h3>
	<div class="container">
	<p><?=$student[about]?></p>
	
	</div>
	
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- End Profile Modal -->
    
    <?php } } ?>
    
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
								<a class="navbar-brand" href="editProfile.php"><span class="glyphicon glyphicon-user"></span> <?= $name ?></a>
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
				<!-- Lecture Assistants Section -->
				<div class="row">
					<div class="container">					
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 data-toggle="collapse" data-target="#lectureTAs">Lecture Assistants</h4>
							</div> <!-- End panel-heading -->
							<div class="collapse panel-collapse" id="lectureTAs">
								<div class="panel-body">
									<form action="selections.php" method="post" id="formid"> 	
									<table class="table table-striped">
										<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th># Positions</th> <th>GPA</th> <th>View Profile</th> <th>Action</th></tr>											
									        <?php 
											
									        foreach($lectureApps as $app){ 
					  
												$academic = getStudentByID($app[0]);
												$buttonGroupName = "actions" . $tableEntry; //used to identify a set unique set of action radio buttons. 
												$myProfileID = "myProfile" . $app[0];
												
					  
										?>
										
										<tr><td><?= $app[0] ?></td> <td><?= $app[1] ?></td> <td><?= $app[2] ?></td> <td><?= $app[3] ?></td><td><?= $app[4] ?></td><td> 2 </td> <td><?= $academic[GPA] ?></td>  <td><a type="button" type="button" data-toggle="modal" href="#<?=$myProfileID?>" class="btn btn-default">
										<span class="glyphicon glyphicon-user"></span> Profile</a>
										</td>
										<td>
											<div class="btn-group" data-toggle="buttons">
												<label name="lectureSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Approve">
												<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="app<?= $app[0] ?>" value="approve"><span class="glyphicon glyphicon-ok"></span>
												</label>
												<label name="lectureSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Reject">
												<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="rej<?= $app[0] ?>" value="reject"><span class="glyphicon glyphicon-remove"></span>
												</label>
												<label name="lectureSelections" class="btn btn-default active" data-toggle="tooltip" data-placement="bottom" title="Undecided">
												<input type="radio" checked="true" name="<?= $buttonGroupName ?>" id="app<?= $app[0] ?>" value="undecided"><span class="glyphicon glyphicon-time"></span>												
												</label>
											</div> <!-- End btn-group -->
										</td>
										</tr> 
										
										<?php
										$tableEntry++;
										} ?>
										
									</table> <!-- End table -->

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
										<div class="progress-bar progress-bar-<?= $lectureBarStatus ?>"  role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?= $lectureRatio*100 ?>%">
										<?= $currentLectureAssistants ?>/<?= $maximumLecturePositions ?> Positions Filled.
										</div> <!-- End progress-bar progress-bar-danger -->
									</div> <!-- End progress-bar -->
								</div> <!-- End panel-footer -->
						</div> <!-- End panel panel-primary -->
					</div> <!-- End container -->
				</div> <!-- End Row -->
				<!-- End Lecture Assistants Section -->
				
				<!-- Lecture Assistants Section -->
				<div class="row">
					<div class="container">					
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 data-toggle="collapse" data-target="#labTAs">Lab Assistants</h4>
							</div> <!-- End panel-heading -->
							<div class="collapse panel-collapse" id="labTAs">
								<div class="panel-body">
									<form action="selections.php" method="post"> 	
									<table class="table table-striped">
										<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th># Positions</th> <th>GPA</th> <th>View Profile</th> <th>Action</th></tr>											
									        <?php 
											
									        foreach($labApps as $app){ 
					  
												$academic = getStudentByID($app[0]);
												$buttonGroupName = "actions" . $tableEntry; //used to identify a set unique set of action radio buttons. 
												$myProfileID = "myProfile" . $app[0];
												
					  
										?>
										
										<tr><td><?= $app[0] ?></td> <td><?= $app[1] ?></td> <td><?= $app[2] ?></td> <td><?= $app[3] ?></td><td><?= $app[4] ?></td><td> 2 </td> <td><?= $academic[GPA] ?></td>  <td><a type="button" type="button" data-toggle="modal" href="#<?=$myProfileID?>" class="btn btn-default">
										<span class="glyphicon glyphicon-user"></span> Profile</a>
										</td>
										<td>
											<div class="btn-group" data-toggle="buttons">
												<label name="labSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Approve">
												<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="app<?= $app[0] ?>" value="approve"><span class="glyphicon glyphicon-ok"></span>
												</label>
												<label name="labSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Reject">
												<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="rej<?= $app[0] ?>" value="reject"><span class="glyphicon glyphicon-remove"></span>
												</label>
												<label name="labSelections" class="btn btn-default active" data-toggle="tooltip" data-placement="bottom" title="Undecided">
												<input type="radio" checked="true" name="<?= $buttonGroupName ?>" id="app<?= $app[0] ?>" value="undecided"><span class="glyphicon glyphicon-time"></span>												
												</label>
											</div> <!-- End btn-group -->
										</td>
										</tr> 
										
										<?php
										$tableEntry++;
										} ?>
										
									</table> <!-- End table -->

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
										<div class="progress-bar progress-bar-<?= $labBarStatus ?>"  role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?= $labRatio*100 ?>%">
										<?= $currentLabAssistants ?>/<?= $maximumLabPositions ?> Positions Filled.
										</div> <!-- End progress-bar progress-bar-danger -->
									</div> <!-- End progress-bar -->
								</div> <!-- End panel-footer -->
						</div> <!-- End panel panel-primary -->
					</div> <!-- End container -->
				</div> <!-- End Row -->
				<!-- End Lecture Assistants Section -->
				
				<!-- Lecture Assistants Section -->
				<div class="row">
					<div class="container">					
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 data-toggle="collapse" data-target="#workshopTAs">Workshop Assistants</h4>
							</div> <!-- End panel-heading -->
							<div class="collapse panel-collapse" id="workshopTAs">
								<div class="panel-body">
									<form action="selections.php" method="post"> 	
									<table class="table table-striped">
										<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th># Positions</th> <th>GPA</th> <th>View Profile</th> <th>Action</th></tr>											
									        <?php 
											
									        foreach($workshopApps as $app){ 
					  
												$academic = getStudentByID($app[0]);
												$buttonGroupName = "actions" . $tableEntry; //used to identify a set unique set of action radio buttons. 
												$myProfileID = "myProfile" . $app[0];
												
					  
										?>
										
										<tr><td><?= $app[0] ?></td> <td><?= $app[1] ?></td> <td><?= $app[2] ?></td> <td><?= $app[3] ?></td><td><?= $app[4] ?></td><td> 2 </td> <td><?= $academic[GPA] ?></td>  <td><a type="button" type="button" data-toggle="modal" href="#<?=$myProfileID?>" class="btn btn-default">
										<span class="glyphicon glyphicon-user"></span> Profile</a>
										</td>
										<td>
											<div class="btn-group" data-toggle="buttons">
												<label name="workshopSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Approve">
												<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="app<?= $app[0] ?>" value="approve"><span class="glyphicon glyphicon-ok"></span>
												</label>
												<label name="workshopSelections" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Reject">
												<input type="radio" checked="false" name="<?= $buttonGroupName ?>" id="rej<?= $app[0] ?>" value="reject"><span class="glyphicon glyphicon-remove"></span>
												</label>
												<label name="workshopSelections" class="btn btn-default active" data-toggle="tooltip" data-placement="bottom" title="Undecided">
												<input type="radio" checked="true" name="<?= $buttonGroupName ?>" id="app<?= $app[0] ?>" value="undecided"><span class="glyphicon glyphicon-time"></span>												
												</label>
											</div> <!-- End btn-group -->
										</td>
										</tr> 
										
										<?php
										$tableEntry++;
										} ?>
										
									</table> <!-- End table -->

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
										<div class="progress-bar progress-bar-<?= $workshopBarStatus ?>"  role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?= $workshopRatio*100 ?>%">
										<?= $currentWorkshopAssistants ?>/<?= $maximumWorkshopPositions ?> Positions Filled.
										</div> <!-- End progress-bar progress-bar-danger -->
									</div> <!-- End progress-bar -->
								</div> <!-- End panel-footer -->
						</div> <!-- End panel panel-primary -->
					</div> <!-- End container -->
				</div> <!-- End Row -->
				<!-- End Lecture Assistants Section -->
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
	