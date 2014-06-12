<?php  
   
	include('staffSession.php');
    
    	ini_set("display_errors",1);
    	
    	

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Edit Student</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<link rel="stylesheet" href="../bootstrapValidator.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script rel="text/javascript" src="../bootstrapValidator.min.js"></script>		
		<script rel="text/javascript" src="findStudentAccount.js"></script>
	</head>
	<body>
		<!-- BEGIN Edit Profile Modal-->
		<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title" id="modalHeader"></h1>
					</div> 
					<div class="modal-body">
						<form action="updateStudentProcess.php" class="form-horizontal" id="updateForm" method="post">
							<div class="row">
								<div class="col-md-4">				
									<div class="form-group"> 				
										<label class="control-label" for="firstName">First Name</label>
											<input id="firstName" type="text" class="form-control" name="firstName">																					
									</div> <!-- End form-group -->											
								</div> <!-- End column -->
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label" for="firstName">Last Name</label>
												<input id="lastName" type="text" class="form-control" name="lastName">													
										</div> <!-- End form-group -->							
									</div>	<!-- End column -->										
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="email">Email</label>
										<input id="email" type="email" class="form-control" name="email">					
									</div> <!-- End form-group -->							
								</div>	<!-- End column -->						
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="mobilePhone">Mobile Phone</label>
										<input id="mobilePhone" type="tel" class="form-control" name="mobilePhone" placeholder="Mobile Phone">
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->
						<legend>Academic Information</legend>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="classYear">Class Year</label>
										<select id="classYear" name="classYear" class="form-control">
											<option>2014</option>
											<option>2015</option>
											<option>2016</option>
											<option>2017</option>
											<option>2018</option>
										</select> <!-- End select -->										
									</div> <!-- End form-group -->
								</div> <!-- End column -->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="major">Major</label>
										<select id="major" name="major" class="form-control">
											<option>Accounting</option>
											<option>Computer Science</option>
											<option>Physics</option>
											<option>Mathematics</option>
											<option>Economics</option>
										</select> <!-- End select -->										
									</div> <!-- End form-group -->
								</div> <!-- End column -->							
							</div> <!-- End Row -->	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="GPA">GPA</label>
										<input id="gpa" type="text" class="form-control" name="gpa" placeholder="GPA">
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->	
							<div class="row">
								<div class="col-md-8">
									<label class="control-label" for="aboutMe">About Me</label>
									<textarea id="aboutMe" class="form-control" name="aboutMe" placeholder="Fill this area with previous experience and relevant qualifications."></textarea>
							</div> <!-- End row -->					
						</form> <!-- End form -->					
					</div> <!-- End modal-body -->
				</div> <!-- End modal-content -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button id="updateButton" type="submit"  name="updateButton" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span> Update</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Edit Profile Modal-->    	
	
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
											<li class="active"><a href="editStudent.php">Edit Account</a></li>
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
				<div class="row">
					<h1>Edit Student</h1>
				</div> <!-- End row -->		
				<div class="container">
					<div class="container" id="search">
						<div class="row">
							<h3>Filter Constraints</h3>
						</div> <!-- end row -->
						<div class="row">
							<form class="form-horizontal" id="searchUsersForm" method="post" action="searchUsers.php">
								<div class="row">
									<div class="col-md-10">
											<label class="control-label" for="emailSearch">Email</label>
											<input id="emailSearch" type="email" class="form-control" name="emailSearch" placeholder="Email">																				
									</div> <!-- End column -->					
								</div> <!-- End row -->
								<div class="row">
									<div class="col-md-10">
											<label class="control-label" for="firstName">First Name</label>
												<input id="firstName" type="text" class="form-control" name="firstName" placeholder="First Name">																				
									</div> <!-- End column -->					
								</div> <!-- End row -->
								<div class="row">
									<div class="col-md-10">
											<label class="control-label" for="lastName">Last Name</label>
												<input id="lastName" type="text" class="form-control" name="lastName" placeholder="Last Name">																				
									</div> <!-- End column -->
									<div class="col-md-4" id="searchType">
										<div class="form-group">
												<input type="text" class="form-control" name="searchType" value="0">													
										</div> <!-- End form-group -->							
									</div>	<!-- End column -->										
								</div> <!-- End row -->								
								<br>
								<div class="row">
									<div class="col-md-6">
										<button id="searchButton"  type="submit" name="searchButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-search"></span> Search</button>
									</div> <!-- End column -->
								</div> <!-- End row -->	
							</form> <!-- End form-horizontal -->
						</div> <!-- End row -->
					</div> <!-- End container -->	
					
					<div class="container" id="result">
						<div class="row">
							<div class="col-md-4">
								<h3 class="btn-default">Results</h3>
							</div> <!-- End column -->
						</div> <!-- End row -->						
						<div class="row">
							<div class="col-md-12">
								<table class="table table-striped" id="resultTable">
									<tr><th>First Name</th><th>Last Name</th><th>email</th><th>Profile</th><tr>
								</table> <!-- End table table-striped -->									
							</div> <!-- End column -->
						</div> <!-- End row -->
						<!-- TODO: Implement pagination every 10 rows -->				
					</div> <!-- End container -->
					<div class="jumbotron" id="emptyResult">
						<div class="row" id="testRow" data-id="14">
							<div class="col-md-12">
								<p>No Students Found</p>
							</div> <!-- End column -->
						</div> <!-- End row -->
					</div> <!-- End jumbotron no results -->					
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