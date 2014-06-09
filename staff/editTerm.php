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
					</div> <!-- End row -->
					
					<div class="container" id="navTabs">
								<form action="#" class="form-horizontal" id="newTermForm" method="post">		
									<div class="row">		
										<div class="col-md-12">
											
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
						<!-- Nav tabs -->
						<ul class="nav nav-stacked">
							<li class="active"><a href="#term" data-toggle="tab">Term Overview</a></li>
							<li><a href="#manageCourses" data-toggle="tab">Manage Courses</a></li>
							<li><a href="#managePositions" data-toggle="tab">Manage Positions</a></li>
						</ul> 
						<!-- End Nav tabs -->					
					</div> <!-- End container -->		
					<div class="container" id="editTermForms">
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
						
						
						<div class="tab-pane fade in" id="manageCourses">						
							<!-- Nav tabs -->
							<ul class="nav nav-pills">
								<li class="active"><a href="#newCourse" data-toggle="tab">New Course</a></li>
								<li><a href="#editCourse" data-toggle="tab">Edit Course</a></li>
							</ul> <!-- End Nav tabs -->
							<div class="tab-content">
								<div class="tab-pane fade in active" id="newCourse">
									
									<form class="form-horizontal" method="post" action="#" id="newCourseForm">
										<legend>Create Course</legend>
										<div class="row">
											<div class="col-md-4">
												<label class="control-label" for="courseNumber">Course Number</label>
												<input type="text" name="courseNumber" placeholder="Course Number" class="form-control">
											</div> <!-- End column -->
											<div class="col-md-4">
												<label class="control-label" for="courseTitle">Course Title</label>
													<input type="text" name="courseTitle" placeholder="Course Title" class="form-control">
											</div> <!-- End column --> 
										</div> <!-- End row -->
										<div class="row">
											<div class="col-md-4">
												<label class="control-label" for="startTime">Start Time</label>
												<input type="time" name="startTime" placeholder="" class="form-control">
											</div> <!-- End column -->
											<div class="col-md-4">
												<label class="control-label" for="endTime">End Time</label>
												<input type="time" name="endTime" placeholder="" class="form-control">
											</div> <!-- End column -->
										</div> <!-- End row -->										
										<div class="row">
											<div class="col-md-4">
												<label class="control-label" for="websiteURL">Website</label>
												<input type="url" name="websiteURL" placeholder="" class="form-control">
											</div> <!-- End column -->
											<div class="col-md-4">						
												<label class="control-label" for="professor">Professor</label>
												<select name="professor" class="form-control">
													<option>Ted Pawlicki</option>
													<option>Chris Brown</option>
													<option>Michael Scott</option>
												</select> <!-- End select -->										
											</div> <!-- End column -->
										</div> <!-- End row -->
										<legend>Location</legend>
										<div class="row">
											<div class="col-md-4">											
												<label class="control-label" for="building">Building</label>
												<select name="building" class="form-control" placeholder="Building">
													<option>CSB</option>
													<option>LATT</option>
													<option>Meliora</option>
												</select> <!-- End select -->										
											</div> <!-- End column -->
											<div class="col-md-4">													
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
											<div class="col-md-2">
												<button id="newCourseButton" type="submit"  name="newCourseButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-plus"></span> Create New Course</button>
											</div> <!-- End column -->											
										</div> <!-- End row -->	
										<br>
									</form> <!-- End form -->													
								</div> <!-- End tab-pane new course -->
								<div class="tab-pane fade" id="editCourse">							
									<div class="container">
										<div class="row">
											<div class="col-md-4">
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
											<div class="col-md-4" id="professorColumn">
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
									</div> <!-- End container -->	
										<div class="container" id="updateCourseContainer">
											<form class="form-horizontal" method="post" action="#" id="updateCourseForm">
												<legend>Update Course</legend>
												<div class="row">
													<div class="col-md-4">
														<label class="control-label" for="courseNumber">Course Number</label>
														<input type="text" name="courseNumber" placeholder="Course Number" class="form-control">
													</div> <!-- End column -->
													<div class="col-md-4">
														<label class="control-label" for="courseTitle">Course Title</label>
														<input type="text" name="courseTitle" placeholder="Course Title" class="form-control">
													</div> <!-- End column --> 
												</div> <!-- End row -->
												<div class="row">
													<div class="col-md-4">
														<label class="control-label" for="startTime">Start Time</label>
														<input type="time" name="startTime" placeholder="" class="form-control">
													</div> <!-- End column -->
													<div class="col-md-4">
														<label class="control-label" for="endTime">End Time</label>
														<input type="time" name="endTime" placeholder="" class="form-control">
													</div> <!-- End column -->
												</div> <!-- End row -->										
												<div class="row">
													<div class="col-md-4">
														<label class="control-label" for="websiteURL">Website</label>
														<input type="url" name="websiteURL" placeholder="" class="form-control">
													</div> <!-- End column -->
													<div class="col-md-4">
														
															<label class="control-label" for="professor">Professor</label>
															<select name="professor" class="form-control">
																<option>Ted Pawlicki</option>
																<option>Chris Brown</option>
																<option>Michael Scott</option>
															</select> <!-- End select -->										
														
													</div> <!-- End column -->
												</div> <!-- End row -->
												<legend>Location</legend>
												<div class="row">
													<div class="col-md-4">
														
															<label class="control-label" for="building">Building</label>
															<select name="building" class="form-control" placeholder="Building">
																<option>CSB</option>
																<option>LATT</option>
																<option>Meliora</option>
															</select> <!-- End select -->										
														
													</div> <!-- End column -->
													<div class="col-md-4">
														
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
													<div class="col-md-2">
														<button id="updateCourseButton" type="submit"  name="updateCourseButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-refresh"></span> Update</button>
													</div> <!-- End column -->
													<div class="col-md-2">
														<button id="removeCourseButton" type="submit"  name="removeCourseButton" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-remove"></span> Remove</button>
													</div> <!-- End column -->														
												</div> <!-- End row -->											
											</form> <!-- End form -->
											<br>
										</div> <!-- End container -->													
								</div> <!-- End tab-pane edit course -->													
							</div> <!-- End tab-content manage courses -->
						</div> <!-- End tab-pane manage courses -->
						
						<div class="tab-pane fade" id="managePositions">
								<div class="container">
									<form class="form-horizontal" method="post" action="#">
										<div class="row">
											<div class="col-md-4">
													<label class="control-label" for="course">Select Course</label>
													<select name="course" class="form-control" id="selectCourse">
														<option>The Science of Data Structures</option>
														<option>Computation and Formal Systems</option>
														<option>Computer Organization</option>
														<option>Web Programming</option>
													</select> <!-- End select -->										
											</div> <!-- End column -->
											<div class="col-md-4" id="professorColumn">
													<label class="control-label" for="professor">Select Professor</label>
													<select name="professor" class="form-control" id="selectProfessor">
														<option>Ted Pawlicki</option>
														<option>Chris Brown</option>
														<option>Michael Scott</option>
													</select> <!-- End select -->										
											</div> <!-- End column -->												
										</div> <!-- End row -->										
									</form> <!-- End form manage positions -->
								</div> <!-- end container -->
								<div class="row">
									<div class="col-md-4">
										<h3>Current Assistants</h3>
									</div> <!-- End column -->
									
								</div> <!-- End row -->
								<div class="row">
									<div class="col-md-12">
									<table class="table table-striped">
										<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th>Type</th> <th>GPA</th><th>View Profile</th></tr>									<tr><td>3</td> <td>Jinze</td> <td>Ahn</td> <td>jan2@u.rochester.edu</td><td>CSC171</td><td>Lab TA</td><td>4.00</td><td><a type="button" type="button" data-toggle="modal" href="#myProfile3" class="btn btn-default">
											<span class="glyphicon glyphicon-user"></span> Profile</a>
											</td>
										</tr> 
										<tr><td>3</td> <td>Jinze</td> <td>Ahn</td> <td>jan2@u.rochester.edu</td><td>CSC171</td><td>Lab TA</td><td>4.00</td><td><a type="button" type="button" data-toggle="modal" href="#myProfile3" class="btn btn-default">
											<span class="glyphicon glyphicon-user"></span> Profile</a>
											</td>
										</tr> 	
										<tr><td>3</td> <td>Jinze</td> <td>Ahn</td> <td>jan2@u.rochester.edu</td><td>CSC171</td><td>Lab TA</td><td>4.00</td><td><a type="button" type="button" data-toggle="modal" href="#myProfile3" class="btn btn-default">
											<span class="glyphicon glyphicon-user"></span> Profile</a>
											</td>
										</tr> 											
									</table>									
									</div> <!-- End column -->
								</div> <!-- End row -->
								<div class="row">
									<div class="col-md-2">
										<button id="updateCourseButton" type="submit"  name="updateCourseButton" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-add"></span> Add Grader</button>
									</div> <!-- End column -->
									<div class="col-md-2 col-md-offset-2">
										<button id="removeCourseButton" type="submit"  name="removeCourseButton" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-add"></span> Add Lab TA</button>
									</div> <!-- End column -->
									<div class="col-md-2 col-md-offset-2">
										<button id="removeCourseButton" type="submit"  name="removeCourseButton" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-add"></span> Add WL</button>
									</div> <!-- End column -->														
								</div> <!-- End row -->									
						</div> <!-- end tab-pane -->
					</div>	<!-- end tab-content -->					
					</div> <!-- End editTermForms container -->
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</body>	
</html>