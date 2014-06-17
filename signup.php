<?php

require('db.php');
require('formInput.php');
require('error.php');
require('email.php');

$signup_success = false;
if (isset($_POST['submitButton'])) {
	$form_args = get_form_values(array(
		'email','emailConfirm','password','passwordConfirm','firstName','lastName',
		'mobilePhone','major','gpa','classYear','aboutMe','universityID'));

	$invalid_values = get_invalid_values($form_args);
	if (count($invalid_values) > 0) {
		Error::setError(Error::FORM_SUBMISSION, 'Error creating an account.',
			$invalid_values);
	} else {
		// manually validate emailConfirm and passwordConfirm fields for now...
		if ($form_args['email'] != $form_args['emailConfirm']) {
			Error::setError(Error::FORM_SUBMISSION, 'Error creating an account.',
				array('emailConfirm'));
		} elseif ($form_args['password'] != $form_args['passwordConfirm']) {
			Error::setError(Error::FORM_SUBMISSION, 'Error creating an account.',
				array('passwordConfirm'));
		} else {
			try {
				$studentID = Student::registerStudent(
					$form_args['email'], $form_args['password'],
					$form_args['firstName'], $form_args['lastName'],
					$form_args['mobilePhone'], $form_args['major'],
					$form_args['gpa'], $form_args['classYear'],
					$form_args['aboutMe'], $form_args['universityID']);
				email_signup_token($studentID, true);
				$signup_success = true;
			} catch (PDOException $ex) {
				Error::setError(Error::EXCEPTION, 'Error creating an account.',
					$ex);
			}
		}
	}
}


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
		<script src="bootstrapValidator.min.js"></script>
		<script src="signup.js"></script>

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
<?php
if ($signup_success) {
?><p>We have sent you an email confirmation (NYI).</p><p>Start seeking available positions by <a href=".">signig in</a> with your email address and password.</p>
<?php
} else {
?>
						<?php Error::putError(); ?>
						<h2>Sign Up</h2>
						<form action="signup.php" class="form-horizontal" id="signupForm" method="post">
							<!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
							<input style="display:none" type="text" name="fakeusernameremembered"/>
							<input style="display:none" type="password" name="fakepasswordremembered"/>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="firstName">First Name</label>
										<input type="text" class="form-control" name="firstName" placeholder="First Name" value="<?=get_form_value('firstName')?>" />
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="firstName">Last Name</label>
										<input type="text" class="form-control" name="lastName" placeholder="Last Name" value="<?=get_form_value('lastName')?>" />
									</div> <!-- End form-group -->							
								</div>							
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="email">Email</label>
										<input type="email" class="form-control" name="email" data-bv-remote-name="email" placeholder="Email" value="<?=get_form_value('email')?>" />
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="emailConfirm">Re-type Email</label>
										<input type="email" class="form-control" name="emailConfirm" autocomplete="off" placeholder="Confirm Email" />
									</div> <!-- End form-group -->							
								</div>							
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="password">Password</label>
										<input type="password" class="form-control" name="password" placeholder="Create Password" />
									</div> <!-- End form-group -->							
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="passwordConfirm">Re-type Password</label>
										<input type="password" class="form-control" name="passwordConfirm" autocomplete="off" placeholder="Confirm Password" />
									</div> <!-- End form-group -->						
								</div>							
							</div> <!-- End row -->	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="mobilePhone">Mobile Phone</label>
										<input type="tel" class="form-control" name="mobilePhone" placeholder="Mobile Phone" value="<?=get_form_value('mobilePhone')?>" />
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
										<input type="text" class="form-control" name="gpa" placeholder="GPA" value="<?=get_form_value('gpa')?>" />
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="universityID">University Student ID</label>
										<input type="text" class="form-control" name="universityID" placeholder="University ID" value="<?=get_form_value('universityID')?>" />
									</div> <!-- End form-group -->
								</div> <!-- End column -->								
							</div> <!-- End row -->	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="aboutMe">About Me</label>
										<textarea class="form-control" name="aboutMe" placeholder="Fill this area with previous experience and relevant qualifications."><?=get_form_value('aboutMe')?></textarea>
									</div> <!-- End form-group -->
								</div> <!-- End row -->
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-md-3">
									<button type="submit"  id="submitButton" name="submitButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-thumbs-up"></span> Sign Up</button>
								</div> <!-- End column -->
							</div> <!-- End row -->								
						</form>
<?php
}
?>
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
