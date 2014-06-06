<?php  
    include('staffSession.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Modify Term</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<link href="modifyTerm.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
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
									<li class="dropdown">
										<a href="manageTerms.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Terms<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="newTerm.php">New Term</a></li>
											<li><a href="modifyTerm.php">Modify Term</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageProfessors.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Professors<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="createProfessor.php">New Account</a></li>
											<li><a href="modifyProfessor.php">Modify Account</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageAssistants.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Assistants<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="modifyStudent.php">Modify Account</a></li>
											<li><a href="verifyStudents.php">Screen Students</a></li>
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
				<div class="container">
					<div class="jumbotron">
						<div id="formBox">
							<form action="#" class="form-horizontal" id="newTermForm" method="post">
								<legend>Modify Term</legend>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label" for="termName">Select Term</label>
											<select id="classYear" name="termName" class="form-control">
												<option>Summer-2014</option>
												<option>Fall-2014</option>
												<option>Spring-2015</option>
												<option>Summer-2015</option>
												<option>Fall-2015</option>
											</select> <!-- End select -->										
										</div> <!-- End form-group -->
									</div> <!-- End column -->									
								</div> <!-- End row -->
							</form> <!-- End form -->													
					</div> <!-- End jumbotron -->
					<!-- Nav tabs -->
					<ul class="nav nav-tabs">
						<li class="active"><a href="#termOverview" data-toggle="tab">Term Overview</a></li>
						<li><a href="#manageCourses" data-toggle="tab">Manage Courses</a></li>
						<li><a href="#managePositions" data-toggle="tab">Manage Positions</a></li>
						<li><a href="#manageLocations" data-toggle="tab">Manage Locations</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane fade in active" id="termOverview">
							<div class="container">
								<br>
								<div class="row">
									<div class="col-md-4">
										<h4>Number of Courses:</h4>
									</div> <!-- End column -->
								</div> <!-- End row -->
								<div class="row">
									<div class="col-md-4">
										<h4>Number of Positions:</h4>
									</div> <!-- End column -->
								</div> <!-- End row -->	
								<div class="row">
									<div class="col-md-4">
										<h4>Number of Locations:</h4>
									</div> <!-- End column -->
								</div> <!-- End row -->								
							</div> <!-- end container -->
						</div> <!-- end tab-pane -->
						<div class="tab-pane fade " id="manageCourses">
							<div class="container">
								<br>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label" for="major">Select Course</label>
											<select name="major" class="form-control" placeholder="Major">
												<option>The Science of Data Structures</option>
												<option>Computation and Formal Systems</option>
												<option>Web Programming</option>
											</select> <!-- End select -->										
										</div> <!-- End form-group -->
									</div> <!-- End column -->
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label" for="major">Select Professor</label>
											<select name="major" class="form-control" placeholder="Major">
												<option>Ted Pawlicki</option>
												<option>Chris Brown</option>
												<option>Michael Scott</option>
											</select> <!-- End select -->										
										</div> <!-- End form-group -->
									</div> <!-- End column -->									
								</div> <!-- End row -->
								
								<div class="row">
									
								</div> <!-- End row -->
								
							</div> <!-- end container -->
						</div> <!-- end tab-pane -->
						<div class="tab-pane fade " id="managePositions">
						</div> <!-- end tab-pane -->
						<div class="tab-pane fade" id="manageLocations">
						</div> <!-- end tab-pane -->
					</div>					
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