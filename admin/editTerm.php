<?php

require_once '../db.php';

$error = null;
$staff = null;
try {
	$staff = LoginSession::sessionContinue(ADMIN);
} catch (TarsException $ex) {
	$error = $ex;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>Edit Term</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="../css/bootstrap-select.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<link href="../favicon.ico" rel="shortcut icon"/>
		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/bootstrap-select.min.js"></script>
		<script src="../js/tars_utilities.js"></script>
		<script src="editTerm.js"></script>
	</head>
	<body>
		<!-- BEGIN Positions Modal-->
		<div class="modal fade" id="positionsModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">New Positions</h1>
					</div> <!-- End modal-header -->
					<div class="modal-body">
							<form action="#" method="post" id="newPositionsForm" class="form-horizontal">
								<fieldset>
									<legend>General</legend>
									<div class="row">
										<div class="col-xs-10">									
										<label class="control-label" for="positionType">Position Type</label>
											<select id="positionType" name="positionType" class="selectpicker form-control">
												<option>Grader</option>
												<option>Workshop Leader</option>
												<option>Lab TA</option>
											</select> <!-- End select -->										
										</div> <!-- End column -->
										<div class="col-xs-2">
											<label class="control-label" for="quantity">Number</label>
											<input type="text" name="quantity" class="form-control" placeholder="">
										</div> <!-- End column -->
									</div> <!-- End row -->
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="startTime">Start Time</label>
													<input type="time" name="startTime" class="form-control">
												</div> <!-- End column -->
												<div class="col-xs-4">
													<label class="control-label" for="endTime">End Time</label>
													<input type="time" name="endTime" class="form-control">
												</div> <!-- End column -->
											</div> <!-- End row -->
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="days">Days</label>
													<select name="days" class="selectpicker form-control" multiple>
														<option>Monday</option>
														<option>Tuesday</option>
														<option>Wednesday</option>
														<option>Thrusday</option>
														<option>Friday</option>
														<option>Saturday</option>
														<option>Sunday</option>
													</select> <!-- End select -->											
												</div> <!-- End column -->											
											</div> <!-- End row -->
											<legend>Location</legend>
											<div class="row">
												<div class="col-xs-4">											
													<label class="control-label" for="building">Building</label>
													<select name="building" class="selectpicker form-control" placeholder="Building">
													</select> <!-- End select -->										
												</div> <!-- End column -->
												<div class="col-xs-4">													
													<label class="control-label" for="rooms">Room</label>
													<select name="room" class="selectpicker form-control" placeholder="Room">
													</select> <!-- End select -->																							
												</div> <!-- End column -->							
											</div> <!-- End Row -->
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
		<!-- END Positions Modal-->    	
		
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Manage
$header_active = 'manage';
require 'header.php';
?>
			<!-- BEGIN Page Content -->
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
				<div class="container" id="termContainer">
					<div class="row">
						<h1>Edit Term</h1>
						<h2 class="termHeader"></h2>
					</div> <!-- End row -->
					<div class="container" id="navTabs">
						<form action="#" class="form-horizontal" id="newTermForm" method="post">		
							<div class="row">		
								<div class="col-xs-8">
									<label class="control-label" for="termName">Select Term</label>
										<select id="selectTerm" name="termName" class="selectpicker form-control">
											<option>Summer-2014</option>
											<option>Fall-2014</option>
											<option>Spring-2015</option>
											<option>Summer-2015</option>
											<option>Fall-2015</option>
										</select> <!-- End select -->										
								</div> <!-- End column -->									
							</div> <!-- End row -->
						</form> <!-- End form -->	
						<div class="row">
							<div class="col-xs-8">
								<!-- Nav tabs -->
								<ul class="nav nav-stacked">
									<li class="active"><a href="#term" data-toggle="tab">Term Overview</a></li>
									<li><a href="#manageCourses" data-toggle="tab">Manage Courses</a></li>
									<li><a href="#managePositions" data-toggle="tab">Manage Positions</a></li>
								</ul> 
								<!-- End Nav tabs -->								
							</div> <!-- End column -->
						</div> <!-- End Row -->
					</div> <!-- End container -->		
					<div id="termPanes">
						<div class="tab-content">				
							<!-- Begin Term Overview Pane -->
							<div class="tab-pane fade in active" id="term">
								<br>
								<div class="container">
								</div> <!-- End container -->							
								<div class="container" id="termOverview">
									<h4>Number of Courses: (Number)</h4>
									<h4>Number of Positions: (Number)</h4>
									<h4>Number of Locations: (Number)</h4>
									<h4>Last Edited: (date)</h4>	
								</div> <!-- End container -->
							</div> <!-- end tab-pane -->
							<!-- End Term Overview Pane -->
							
							<!-- BEGIN Manage Courses Pane -->
							<div class="tab-pane fade in" id="manageCourses">						
								<div class="row">
									<div class="col-xs-4">
										<!-- Nav tabs -->
										<ul class="nav nav-pills">
											<li class="active"><a href="#newCourse" data-toggle="tab">New Course</a></li>
											<li><a href="#editCourse" data-toggle="tab">Edit Course</a></li>
										</ul> <!-- End Nav tabs -->									
									</div> <!-- End column -->
								</div> <!-- End row -->
								<div class="tab-content">
									<div class="tab-pane fade in active" id="newCourse">
										<form class="form-horizontal" method="post" action="#" id="newCourseForm">
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="courseCRN">Course CRN</label>
													<input type="text" name="courseCRN" placeholder="Course CRN" class="form-control">
												</div> <!-- End column -->
												<div class="col-xs-4">
													<label class="control-label" for="courseTitle">Course Title</label>
														<input type="text" name="courseTitle" placeholder="e.g. The Science of Data Structures" class="form-control">
												</div> <!-- End column --> 
											</div> <!-- End row -->									
											<div class="row">
												<div class="col-xs-4">						
													<label class="control-label" for="professor">Professor</label>
													<select name="professor" class="selectpicker form-control all-professors">
													</select> <!-- End select -->										
												</div> <!-- End column -->											
												<div class="col-xs-4">
													<label class="control-label" for="websiteURL">Website</label>
													<input type="url" name="websiteURL" placeholder="" class="form-control">
												</div> <!-- End column -->
											</div> <!-- End row -->
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="startTime">Start Time</label>
													<input type="time" name="startTime" class="form-control">
												</div> <!-- End column -->
												<div class="col-xs-4">
													<label class="control-label" for="endTime">End Time</label>
													<input type="time" name="endTime" class="form-control">
												</div> <!-- End column -->
											</div> <!-- End row -->
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="days">Days</label>
													<select name="days" class="selectpicker form-control" multiple>
														<option>Monday</option>
														<option>Tuesday</option>
														<option>Wednesday</option>
														<option>Thrusday</option>
														<option>Friday</option>
														<option>Saturday</option>
														<option>Sunday</option>
													</select> <!-- End select -->											
												</div> <!-- End column -->											
											</div> <!-- End row -->
											<legend>Location</legend>
											<div class="row">
												<div class="col-xs-4">											
													<label class="control-label" for="buildings">Building</label>
													<select name="building" class="selectpicker form-control buildings" placeholder="Building">
													</select> <!-- End select -->										
												</div> <!-- End column -->
												<div class="col-xs-4">													
													<label class="control-label" for="room">Room</label>
													<select name="room" class="form-control rooms" placeholder="Room">
													</select> <!-- End select -->																							
												</div> <!-- End column -->							
											</div> <!-- End Row -->
											<br>
											<div class="row">
												<div class="col-xs-4">
													<button id="newCourseButton" type="submit"  name="newCourseButton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Create New Course</button>
												</div> <!-- End column -->											
											</div> <!-- End row -->	
											<br>
										</form> <!-- End form -->													
									</div> <!-- End tab-pane new course -->
									<div class="tab-pane fade" id="editCourse">							
											<div class="row">
												<div class="col-xs-4">
													<div class="form-group">
														<label class="control-label" for="course">Select Course</label>
														<select name="course" class="selectpicker form-control courses" placeholder="Courses">
														</select> <!-- End select -->										
													</div> <!-- End form-group -->
												</div> <!-- End column -->
												<div class="col-xs-4" id="professorColumn">
													<div class="form-group">
														<label class="control-label" for="professor">Select Professor</label>
														<select name="professor" class="selectpicker form-control professors" placeholder="Professors">
														</select> <!-- End select -->										
													</div> <!-- End form-group -->
												</div> <!-- End column -->									
											</div> <!-- End row -->	
										<form class="form-horizontal" method="post" action="#" id="newCourseForm">
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="courseCRN">Course CRN</label>
													<input type="text" name="courseCRN" placeholder="Course CRN" class="form-control">
												</div> <!-- End column -->
												<div class="col-xs-4">
													<label class="control-label" for="courseTitle">Course Title</label>
														<input type="text" name="courseTitle" placeholder="e.g. The Science of Data Structures" class="form-control">
												</div> <!-- End column --> 
											</div> <!-- End row -->									
											<div class="row">
												<div class="col-xs-4">						
													<label class="control-label" for="professor">Professor</label>
													<select name="professor" class="selectpicker form-control all-professors">
													</select> <!-- End select -->										
												</div> <!-- End column -->											
												<div class="col-xs-4">
													<label class="control-label" for="websiteURL">Website</label>
													<input type="url" name="websiteURL" placeholder="" class="form-control">
												</div> <!-- End column -->
											</div> <!-- End row -->
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="startTime">Start Time</label>
													<input type="time" name="startTime" class="form-control">
												</div> <!-- End column -->
												<div class="col-xs-4">
													<label class="control-label" for="endTime">End Time</label>
													<input type="time" name="endTime" class="form-control">
												</div> <!-- End column -->
											</div> <!-- End row -->
											<div class="row">
												<div class="col-xs-4">
													<label class="control-label" for="days">Days</label>
													<select name="days" class="selectpicker form-control" multiple>
														<option>Monday</option>
														<option>Tuesday</option>
														<option>Wednesday</option>
														<option>Thrusday</option>
														<option>Friday</option>
														<option>Saturday</option>
														<option>Sunday</option>
													</select> <!-- End select -->											
												</div> <!-- End column -->											
											</div> <!-- End row -->
											<legend>Location</legend>
											<div class="row">
												<div class="col-xs-4">											
													<label class="control-label" for="buildings">Building</label>
													<select name="buildings" class="selectpicker form-control buildings" placeholder="Building">
													</select> <!-- End select -->										
												</div> <!-- End column -->
												<div class="col-xs-4">													
													<label class="control-label" for="rooms">Room</label>
													<select name="rooms" class="selectpicker form-control rooms" placeholder="Room">
													</select> <!-- End select -->																							
												</div> <!-- End column -->							
											</div> <!-- End Row -->
											<br>
											<div class="row">
												<div class="col-xs-4">
													<button id="updateCourseButton" type="submit"  name="newCourseButton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Update</button>
												</div> <!-- End column -->											
											</div> <!-- End row -->	
											<br>
										</form> <!-- End form -->																
									</div> <!-- End tab-pane edit course -->													
								</div> <!-- End tab-content manage courses -->
							</div> 
							<!-- END tab-pane Manage Courses -->
							
							<!-- BEGIN tab-pane Mange Positions -->
							<div class="tab-pane fade" id="managePositions">
								<form class="form-horizontal" method="post" action="#">
									<div class="row">
										<div class="col-xs-4">
											<label class="control-label" for="course">Select Course</label>
											<select name="course" class="selectpicker form-control courses">
											</select> <!-- End select -->										
										</div> <!-- End column -->
										<div class="col-xs-4" id="professorColumn">
											<label class="control-label" for="professor">Select Professor</label>
											<select name="professor" class="selectpicker form-control professors">
											</select> <!-- End select -->										
										</div> <!-- End column -->												
									</div> <!-- End row -->										
								</form> <!-- End form manage positions -->
								<div class="row">
									<div class="col-xs-4">
										<h3>Current Assistants</h3>
									</div> <!-- End column -->							
								</div> <!-- End row -->
								<div class="row">
									<div class="col-xs-12">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>ID</th>
												<th>First Name</th>
												<th>Last Name</th>
												<th>Email</th>
												<th>Course</th>
												<th>Type</th> 
												<th>GPA</th>
												<th>Profile</th>
											</tr>										
										</thead>
										<tbody>
										<!--TODO: Render each row dynamically using the Course and Professor -->
											<tr>
												<td>3</td> 
												<td>Jinze</td>
												<td>Ahn</td>
												<td>jan2@u.rochester.edu</td>
												<td>CSC171</td>
												<td>Lab TA</td>
												<td>4.00</td>
												<td>
													<button data-toggle="modal" data-target="#studentProfileModal" class="btn btn-default circle profile">
														<span class="glyphicon glyphicon-user"></span>
													</button>			
												</td>
											</tr> 											
										</tbody>										
									</table>									
									</div> <!-- End column -->
								</div> <!-- End row -->
								<div class="row">
									<div class="col-xs-4">
										<button id="addPositions" data-toggle="modal" data-target="#positionsModal" name="addPositions" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add Positions</button>
									</div> <!-- End column -->
									<div class="col-xs-4">
										<button id="removePositions" data-toggle="modal" data-target="#" name="removePositions" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Remove Positions</button>
									</div> <!-- End column -->	
								</div> <!-- End row -->									
							</div>
						</div>	<!-- end tab-content -->
					</div> <!-- End termPanes -->
				</div> <!-- End container -->
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
