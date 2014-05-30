<?php

	include ("db.php");

	$userName = $_POST['email'];
	$password = $_POST['password'];
	

	$user = login($userName, $password);

	if(isset($_POST['submit'])){
	
		if($user){
		
			/* User type */
			$type = $user['type'];
			
			if($type == STUDENT){
				
				header('Location: student/student.php');
				
			}elseif($type == PROFESSOR){
			
				header('Location: professor/professor.php');
			
			}else{
			
				header('Location: staff/staff.php');

			}
	
		}else{

			header('Location: index.php');
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
		
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="index.css" rel="stylesheet">

	</head>
  
	<body>
		<!-- BEGIN Forgot Password -->
		<div class="modal fade" id="passmodal" tabindex="-1" role="dialog" aria-labelledby="passModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Password Recovery</h1>
					</div>
					<div class="modal-body">
						<p>Enter the email address you used to register for your account and further instructions will be sent to that address.
							This would probably be your school E-mail address.</p>
						<form action="passrecov.php" method="post" id="passrecov">
							<fieldset>
								<div class="row">
									<div class="col-xs-6">
										E-mail: <input type="email" name="passrecov" class="form-control" size="64"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="passrecov" value="Submit">Submit</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Forgot Password -->
		<!-- BEGIN Report a Bug -->
		<div class="modal fade" id="bugmodal" tabindex="-1" role="dialog" aria-labelledby="bugModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Bug Report</h1>
					</div>
					<div class="modal-body">
						<p>
							Enter your information so we can verify your identity.<br/>
							In the space provided, detail the bug that you have encountered as clearly and concisely as you can muster.
						</p>
						<form action="bugrep.php" method="post" id="bugrep">
							<fieldset>
								<div class="row">
									<div class="col-xs-5 col-xs-offset-1">
										<label>
											E-mail: <input class="form-control" type="email" name="bugrep" size="32"/>
										</label>
									</div>
									<div class="col-xs-5">
										<label>
											Password: <input class="form-control" type="password" name="bugreppass" size="32"/>
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<label>
											<textarea class="form-control" rows="4" cols="64"></textarea>
										</label>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="bugrep" value="Submit">Submit</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Report a Bug -->

		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
			
			<!-- BEGIN Page Header -->
			<div id="header">

			</div>		
			<!--END Page Header -->	  
	  
			<!-- BEGIN Page Content -->
			<div id="content">
				<div id="login">
					<form action="index.php" method="post">
						<fieldset>
							<h2 class="center" id="colorWhite">Sign In</h2>
							<div class="row">
								<div class="col-md-10">
									<label id="colorWhite">Email</label>
									<input type="email" name="email" class="form-control" place-holder="">
								</div> <!-- End column -->
							</div> <!-- End row -->
							<div class="row">
								<div class="col-md-10">
									<label id="colorWhite">Password</label>
									<input type="password" name="password" class="form-control" place-holder="">
								</div> <!-- End column -->
							</div> <!-- End row -->	
							<br>
							<div class="row">
								<div class="col-md-3">
									<button name="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-hand-right"></span> Login</button>
								</div> <!-- End column -->
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-md-4">
									<a href="signup.php">Sign Up</a>
								</div> <!-- End column -->
								<div class="col-md-6">
									<div class="form-group">
										<a href="#passmodal" data-toggle="modal">
											Forgot Password?
										</a>
									</div>
								</div> <!-- End column -->								
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-md-4">
									<a href="#bugmodal" data-toggle="modal">
										Report A Bug
									</a>
								</div> <!-- End column -->							
							</div> <!-- End row -->							
						</fieldset> <!-- End fieldset -->
					</form> <!-- End form -->
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
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- END Scripts -->
	</body>
</html>