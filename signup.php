<?php
	include ("dbinterface.php");
// 	if(isset($_POST['submitButton'])){
// 		echo "sdfsdfa";
// 	}else{
// 		echo "not set";
// 	}
// 	
// 	print_r($_POST);
	
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>TARS</title>

		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="signup.css" rel="stylesheet"/>
		<link rel="stylesheet" href="bootstrapValidator.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script rel="text/javascript" src="signup.js"></script>
		<script rel="text/javascript" src="bootstrapValidator.min.js"></script>


	</head>
  
	<body>

		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
			
			<!-- BEGIN Page Header -->
			<div id="header">

			</div>		
			<!--END Page Header -->	  
	  
			<!-- BEGIN Page Content -->
			
			<div id="content">
				<div class="container">
					<div class="jumbotron" id="signupForm">
						<h2>Sign Up</h2>
						<form action="signup2.php" class="form-horizontal" id="signupForm" method="post">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="firstName">First Name</label>
										<input type="text" class="form-control" name="firstName" placeholder="First Name"/>														
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="firstName">Last Name</label>
										<input type="text" class="form-control" name="lastName" placeholder="Last Name"/>													
									</div> <!-- End form-group -->							
								</div>							
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="email">Email</label>
										<input type="email" class="form-control" name="email" placeholder="Email"/>					
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="emailConfirm">Re-Enter Email</label>
										<input type="email" class="form-control" name="emailConfirm" placeholder="Email"/>
														
									</div> <!-- End form-group -->							
								</div>							
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="password">Create Password</label>
										<input type="password" class="form-control" name="password" placeholder="Create Password"/>					
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="passwordConfirm">Confirm Password</label>
										<input type="password" class="form-control" name="passwordConfirm" placeholder="Confirm Password"/>					
									</div> <!-- End form-group -->						
								</div>							
							</div> <!-- End row -->	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="homePhone">Home Phone</label>
										<input type="tel" class="form-control" name="homePhone" placeholder="Home Phone"/>
									</div> <!-- End form-group -->
								</div> <!-- End column -->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="mobilePhone">Mobile Phone</label>
										<input type="tel" class="form-control" name="mobilePhone" placeholder="Mobile Phone"/>
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->
							<legend>Academic Information</legend>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="classYear">Class Year</label>
										<select name="classYear" class="form-control" placeholder="Class Year">
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
										<select name="major" class="form-control" placeholder="Major">
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
										<input type="text" class="form-control" name="gpa" placeholder="GPA"/>
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->	
							<br>
							<div class="row">
								<div class="col-md-3">
									<button id="formButton" type="submit" name="submitButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-thumbs-up"></span> Sign Up</button>
								</div> <!-- End column -->
							</div> <!-- End row -->								
						</form>
					</div> <!-- End jumbotron -->
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