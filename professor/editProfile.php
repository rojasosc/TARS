<?php 
    include('../dbinterface.php');
    session_start(); 
    if (!isset($_SESSION['auth'])) {

    // if not redirect to login screen. 
    
    header('Location: ../index.php');

    }else{
  
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    
    $firstLetter = $firstName[0]; /* Holds the first letter of the first name. */
    $firstLetter .= ".";
    $name = $firstLetter . " " . $lastName;
    
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
		
		<title>My Profile</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
								<a class="navbar-brand" href="editProfile.php"><span class="glyphicon glyphicon-user"></span> <?= $name ?></a>
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
					<form action="editProfile.php" method="post">
						<div class="container">
							<div class="jumbotron">
								<fieldset>
									<legend>Edit Profile</legend>
									<div class="row">
										<div class="col-md-4">
											<label>First Name</label>
											<input type="text" class="form-control" name="firstName" place-holder="place holder">
										</div> <!--End column-->
										<div class="col-md-4">
											<label>Last Name</label>
											<input type="text" class="form-control" name="firstName" place-holder="place holder">
										</div> <!--End column-->
									</div> <!-- End row -->
									<div class="row">
										<div class="col-md-4">
											<label>Email</label>
											<input type="email" class="form-control" name="email" place-holder="place holder">
										</div> <!-- End column -->
										<div class="col-md-4">
											<label>Re-Enter Email</label>
											<input type="email" class="form-control" name="emailConfirm" place-holder="place holder">									
										</div> <!-- End column -->									
									</div> <!-- End row -->
									<div class="row">
										<div class="col-md-4">
											<label>Password</label>
											<input type="password" class="form-control" name="password" place-holder="place holder">
										</div> <!--End column-->
										<div class="col-md-4">
											<label>Re-Enter Password</label>
											<input type="password" class="form-control" name="passwordConfirm" place-holder="place holder">
										</div> <!--End column-->
									</div> <!-- End row -->
									<div class="row">
										<div class="col-md-4">
											<label>Home Phone</label>
											<input type="tel" class="form-control" name="homePhone" place-holder="place holder">
										</div> <!--End column-->
										<div class="col-md-4">
											<label>Cell Phone</label>
											<input type="tel" class="form-control" name="cellPhone" place-holder="place holder">
										</div> <!--End column-->
									</div> <!-- End row -->
									<br>
									<div class="row">
										<div class="col-md-3">
											<button name="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-refresh"></span> Update</button>
										</div> <!-- End column -->
									</div> <!-- End row --> 
								</fieldset> <!-- End fieldset -->
							</div> <!-- End jumbotron -->
						</div> <!-- End container -->				
					</form> <!-- End form -->
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
