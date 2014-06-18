<?php  
    include('staffSession.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Edit Term</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="editTerm.js"></script>
	</head>
	<body>
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
									<li class="dropdown active">
										<a href="manageTerms.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Terms<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="newTerm.php">New Term</a></li>
											<li class="active"><a href="editTerm.php">Edit Term</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageProfessors.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Professors<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="createProfessor.php">New Account</a></li>
											<li class="active"><a href="editProfessor.php">Edit Account</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageAssistants.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Assistants<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="editStudent.php">Edit Account</a></li>
											<li><a href="reviewStudents.php">Review Students</a></li>
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
										<select id="selectTerm" name="termName" class="form-control">
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
													<select name="professor" class="form-control">
														<!--TODO: We need to use AJAX to render these options dynamically. -->
														<option>Ted Pawlicki</option>
														<option>Chris Brown</option>
														<option>Michael Scott</option>
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
													<select name="days" class="form-control" multiple>
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
													<select name="building" class="form-control" placeholder="Building">
														<option>CSB</option>
														<option>LATT</option>
														<option>Meliora</option>
													</select> <!-- End select -->										
												</div> <!-- End column -->
												<div class="col-xs-4">													
													<label class="control-label" for="room">Room</label>
													<select name="room" class="form-control" placeholder="Room">
														<option>306</option>
														<option>233</option>
														<option>255</option>
														<option>219</option>
														<option>393</option>
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
														<select name="course" class="form-control" id="selectCourse">
															<option>The Science of Data Structures</option>
															<option>Computation and Formal Systems</option>
															<option>Computer Organization</option>
															<option>Web Programming</option>
														</select> <!-- End select -->										
													</div> <!-- End form-group -->
												</div> <!-- End column -->
												<div class="col-xs-4" id="professorColumn">
													<div class="form-group">
														<label class="control-label" for="professor">Select Professor</label>
														<select name="professor" class="form-control" id="selectProfessor">
															<option>Ted Pawlicki</option>
															<option>Chris Brown</option>
															<option>Michael Scott</option>
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
													<select name="professor" class="form-control">
														<!--TODO: We need to use AJAX to render these options dynamically. -->
														<option>Ted Pawlicki</option>
														<option>Chris Brown</option>
														<option>Michael Scott</option>
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
													<select name="days" class="form-control" multiple>
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
													<select name="building" class="form-control" placeholder="Building">
														<option>CSB</option>
														<option>LATT</option>
														<option>Meliora</option>
													</select> <!-- End select -->										
												</div> <!-- End column -->
												<div class="col-xs-4">													
													<label class="control-label" for="room">Room</label>
													<select name="room" class="form-control" placeholder="Room">
														<option>306</option>
														<option>233</option>
														<option>255</option>
														<option>219</option>
														<option>393</option>
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
											<select name="course" class="form-control" id="selectCourse">
												<option>The Science of Data Structures</option>
												<option>Computation and Formal Systems</option>
												<option>Computer Organization</option>
												<option>Web Programming</option>
											</select> <!-- End select -->										
										</div> <!-- End column -->
										<div class="col-xs-4" id="professorColumn">
											<label class="control-label" for="professor">Select Professor</label>
											<select name="professor" class="form-control" id="selectProfessor">
												<option>Ted Pawlicki</option>
												<option>Chris Brown</option>
												<option>Michael Scott</option>
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
									<div class="col-xs-2">
										<button id="addGrader" name="addGrader" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add Grader</button>
									</div> <!-- End column -->
									<div class="col-xs-2 col-xs-offset-2">
										<button id="addLabTA" type="submit"  name="addLabTA" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add Lab TA</button>
									</div> <!-- End column -->
									<div class="col-xs-2 col-xs-offset-2">
										<button id="addWL" type="submit"  name="addWL" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add WL</button>
									</div> <!-- End column -->														
								</div> <!-- End row -->									
							</div>
						</div>	<!-- end tab-content -->
					</div> <!-- End termPanes -->
				</div> <!-- End container -->						
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