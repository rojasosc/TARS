<?php

require_once 'db.php';


?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>TARS Sign Up</title>

		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="css/bootstrap-validator.min.css"/>
		<link rel="stylesheet" href="css/bootstrap-select.min.css"/>
		<link href="signup.css" rel="stylesheet"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap-validator.min.js"></script>
		<script src="js/bootstrap-select.min.js"></script>
		<script src="signup.js"></script>
		<script src="js/tars_utilities.js"></script>

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
					<div class="jumbotron" id="formBox">
						<h2>TARS Sign Up</h2>
						<div class="row" id="alertHolder"></div>
						<form action="process.php" class="form-horizontal" id="signupForm" method="post">
							<!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
							<input style="display:none" type="text" name="fakeusernameremembered"/>
							<input style="display:none" type="password" name="fakepasswordremembered"/>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="firstName">First Name</label>
										<input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" />
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="lastName">Last Name</label>
										<input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" />
									</div> <!-- End form-group -->							
								</div>							
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="email">Email</label>
										<input type="email" class="form-control" id="email" name="email" data-bv-remote-name="email" placeholder="Email" />
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="emailConfirm">Re-type Email</label>
										<input type="email" class="form-control" id="emailConfirm" name="emailConfirm" autocomplete="off" placeholder="Confirm Email" />
									</div> <!-- End form-group -->							
								</div>							
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="password">Password</label>
										<input type="password" class="form-control" id="password" name="password" placeholder="Create Password" />
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="passwordConfirm">Re-type Password</label>
										<input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" autocomplete="off" placeholder="Confirm Password" />
									</div> <!-- End form-group -->						
								</div>							
							</div> <!-- End row -->	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="mobilePhone">Mobile Phone</label>
										<div class="input-group">
											<span class="input-group-addon">+1</span>
											<input type="tel" class="form-control" id="mobilePhone" name="mobilePhone" placeholder="ex. 555 555 5555" maxlength="14" />
										</div>
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->
							<legend>Academic Information</legend>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="classYear">Class Year</label>
										<select id="classYear" name="classYear" class="selectpicker form-control" placeholder="Class Year">
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
										<select id="major" name="major" class="selectpicker form-control" placeholder="Major">
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
										<label class="control-label" for="gpa">Cumulative GPA</label>
										<input type="text" class="form-control" id="gpa" name="gpa" placeholder="ex. 3.500" maxlength="5" />
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="universityID">University Student ID</label>
										<input type="text" class="form-control" id="universityID" name="universityID" placeholder="ex. 27400000" maxlength="8" />
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->	
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label" for="aboutMe">Qualifications and TA-ing History</label>
										<textarea class="form-control" id="aboutMe" name="aboutMe" placeholder="Fill this area with previous experience and relevant qualifications." rows="8" cols="64"></textarea>
									</div> <!-- End form-group -->
								</div> <!-- End row -->
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-md-3">
									<button type="submit"  id="submitButton" name="submitButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-thumbs-up"></span> Sign Up</button>
								</div> <!-- End column -->
								<div class="col-md-3">
									<a type="button" class="btn btn-danger btn-block" href="index.php">Cancel</a>
								</div>
							</div> <!-- End row -->								
						</form>
					</div> <!-- End jumbotron -->			
				</div> <!-- End container -->
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
