<?php

	include ("dbinterface.php");

	$account = $_POST[email];
	$pw = $_POST[password];
	
	$validate = login($account, $pw);

	if($validate){
	
	    if($validate[3] == 1){
	    
	      $type = "admin";
	      header('Location: admin/admin.html');
	    
	    }elseif($validate[3] == 2){
	      header('Location: professor/professor.php');
	    
	    }elseif($validate[3] == 3){
	    
	      header('Location: student/student.php');
	      
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
									<a href="#">Forgot Password?</a>
								</div> <!-- End column -->								
							</div> <!-- End row -->
							<br>
							<div class="row">
								<div class="col-md-4">
									<a href="#">Report A Bug</a>
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
		<script src="../js/bootstrap.min.js"></script>
		<!-- END Scripts -->
	</body>
</html>