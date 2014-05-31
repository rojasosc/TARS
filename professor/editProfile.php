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
		
		<title>My Profile</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
		<link rel="stylesheet" href="../bootstrapValidator.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="editProfile.js"></script>
		<script rel="text/javascript" src="../bootstrapValidator.min.js"></script>
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
								<a class="navbar-brand" href="editProfile.php"><span class="glyphicon glyphicon-user"></span> <?= $nameBrand ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="professor.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li><a href="assistants.php"><span class="glyphicon glyphicon-th-list"></span> Assistants</a></li>
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
				<div class="row">
						<div class="container">
							<div class="jumbotron">
								<form action="editProfile.php" method="post" id="editProfileForm">
									<fieldset>
										<legend>Edit Profile</legend>
										
										<div class="row">
											<div class="col-md-4">
												<label>Current Password</label>
												<input type="password" class="form-control" name="currentPassword" place-holder="Enter Current Password">
											</div>
										</div>				
										<div class="row first">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label" for="firstName">First Name</label>
													<input type="text" class="form-control" disabled="disabled" name="firstName" value="<?= $firstName ?>" />													
												</div> <!-- End form-group -->
											</div> <!--End column-->
											<div class="col-md-4">
												<div class="form-group">
													<label>Last Name</label>
													<input type="text" class="form-control" disabled="disabled" name="lastName" value="<?= $lastName ?>">											
												</div> <!-- End form-group -->
											</div> <!--End column-->
											<div class="col-md-4">
												<span id="first" class="glyphicon glyphicon-edit"></span> 
											</div> <!-- End column -->
										</div> <!-- End row -->
										<div class="row second">
											<div class="col-md-4">
												<div class="form-group">
													<label>Email</label>
													<input type="email" class="form-control" disabled="disabled" name="email" value="<?= $email ?>">												
												</div> <!-- End form-group -->																						
											</div> <!-- End column -->
											<div class="col-md-4">
												<div class="form-group">
													<label>Re-Enter Email</label>
													<input type="email" class="form-control" disabled="disabled" name="emailConfirm" >													
												</div> <!-- End form-group -->																				
											</div> <!-- End column -->									
											<div class="col-md-4">
												<span id="second" class="glyphicon glyphicon-edit"></span> 
											</div>									
										</div> <!-- End row -->
										<div class="row third">
											<div class="col-md-4">
												<div class="form-group">
													<label>New Password</label>
													<input type="password" class="form-control" disabled="disabled" name="password" place-holder="Enter your new password.">												
												</div> <!-- End form-group -->											
											</div> <!--End column-->
											<div class="col-md-4">
												<div class="form-group">
													<label>Re-Enter New Password</label>
													<input type="password" class="form-control" disabled="disabled" name="passwordConfirm" place-holder="Re-enter your new password">
											</div> <!--End column-->
												</div> <!-- End form-group -->											
											<div class="col-md-4">
												<span id="third" class="glyphicon glyphicon-edit"></span> 
											</div>									
										</div> <!-- End row -->
										<div class="row fourth">
											<div class="col-md-4">
												<div class="form-group">
													<label>Home Phone</label>
													<input type="tel" class="form-control" disabled="disabled" name="homePhone" value="<?= $professor['homePhone']?>">												
												</div> <!-- End form-group -->											
											</div> <!--End column-->
											<div class="col-md-4">
												<div class="form-group">
													<label>Mobile Phone</label>
													<input type="tel" class="form-control" disabled="disabled" name="mobilePhone" value="<?= $professor['phone']?>">												
												</div> <!-- End form-group -->											
											</div> <!--End column-->
											<div class="col-md-4">
												<span id="fourth" class="glyphicon glyphicon-edit"></span> 
											</div>									
										</div> <!-- End row -->
										<br>
										<div class="row">
											<div class="col-md-3">
												<button type="submit" name="submitButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-refresh"></span> Update</button>
											</div> <!-- End column -->
										</div> <!-- End row --> 
									</fieldset> <!-- End fieldset -->
								</form> <!-- End form -->
							</div> <!-- End jumbotron -->
						</div> <!-- End container -->				
				</div> <!-- End row -->	
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
