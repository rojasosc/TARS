
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
								<a class="navbar-brand" href="editProfile.php"><span class="glyphicon glyphicon-user"></span> R. McDonald</a>
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
				<!-- Lecture Assistants Section -->
				<div class="row">
					<div class="container">					
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 data-toggle="collapse" data-target="#lectureTAs">Lecture Assistants</h4>
							</div> <!-- End panel-heading -->
							<div class="collapse panel-collapse" id = "lectureTAs">
								<div class="panel-body">
										
									<table class="table table-striped">
										<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th>View Profile</th> </tr>
									</table> <!-- End table -->
								</div> <!-- End panel-body -->
							</div> <!-- End collapse panel-collapse -->
							<div class="panel-footer"><a type="button" type="button" data-toggle="modal" href="#emailTAs" class="btn btn-default">
								<span class="glyphicon glyphicon-envelope"></span> Email</a>
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
							<div class="collapse panel-collapse" id = "labTAs">
								<div class="panel-body">										
									<table class="table table-striped">
										<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th>View Profile</th> </tr>
									</table> <!-- End table -->
								</div> <!-- End panel-body -->
							</div> <!-- End collapse panel-collapse -->
							<div class="panel-footer"><a type="button" type="button" data-toggle="modal" href="#emailTAs" class="btn btn-default">
								<span class="glyphicon glyphicon-envelope"></span> Email</a>
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
							<div class="collapse panel-collapse" id = "workshopTAs">
								<div class="panel-body">
										
									<table class="table table-striped">
										<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th>View Profile</th> </tr>
									</table> <!-- End table -->
								</div> <!-- End panel-body -->
							</div> <!-- End collapse panel-collapse -->
							<div class="panel-footer"><a type="button" type="button" data-toggle="modal" href="#emailTAs" class="btn btn-default">
								<span class="glyphicon glyphicon-envelope"></span> Email</a>
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
	