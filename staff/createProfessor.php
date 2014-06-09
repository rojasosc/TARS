<?php  
    include('staffSession.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Create Account</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<!--<link href="newTerm.css" rel="stylesheet">-->
		<link rel="stylesheet" href="../bootstrapValidator.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script rel="text/javascript" src="../bootstrapValidator.min.js"></script>
		<script src="createProfessor.js"></script>

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
											<li><a href="editTerm.php">Edit Term</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown active">
										<a href="manageProfessors.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Professors<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li class="active"><a href="createProfessor.php">New Account</a></li>
											<li><a href="editProfessor.php">Edit Account</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageStudents.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Students<b class="caret"></b></a>
										<ul class="dropdown-menu">											
											<li><a href="reviewStudents.php">Review Students</a></li>
											<li><a href="editStudent.php">Edit Account</a></li>
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
				<div class="container" id="formBox">
						<div class="panel panel-success">
							<div class="panel-heading">
								<p class="panelHeader">New Professor Account</p>
							</div> <!-- End panel-heading -->
							<div class="collapse panel-collapse" id="createAccountBody">
								<div class="panel-body">
									<form class="form-horizontal" id="createAccountForm" method="post" action="createProfessorProcess.php">
										<fieldset>
											<legend>New Account</legend>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="firstName">First Name</label>
														<input type="text" class="form-control" name="firstName" placeholder="First Name"/>														
													</div> <!-- End form-group -->							
												</div> <!-- End column -->
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="firstName">Last Name</label>
														<input type="text" class="form-control" name="lastName" placeholder="Last Name"/>													
													</div> <!-- End form-group -->							
												</div>	<!-- End column -->						
											</div> <!-- End row -->
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="email">Email</label>
														<input type="email" class="form-control" name="email" placeholder="Email"/>					
													</div> <!-- End form-group -->							
												</div> <!-- End column -->
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="emailConfirm">Re-Enter Email</label>
														<input type="email" class="form-control" name="emailConfirm" placeholder="Email"/>
																		
													</div> <!-- End form-group -->							
												</div>	<!-- End column -->						
											</div> <!-- End row -->
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="password">Create Password</label>
														<input type="password" class="form-control" name="password" placeholder="Create Password"/>					
													</div> <!-- End form-group -->							
												</div> <!-- End column -->
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
														<label class="control-label" for="homePhone">Office Phone</label>
														<input type="tel" class="form-control" name="officePhone" placeholder="Office Phone"/>
													</div> <!-- End form-group -->
												</div> <!-- End column -->
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="mobilePhone">Mobile Phone</label>
														<input type="tel" class="form-control" name="mobilePhone" placeholder="Mobile Phone"/>
													</div> <!-- End form-group -->
												</div> <!-- End column -->								
											</div> <!-- End row -->
											<legend>Office</legend>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="building"></label>
														<select name="building" class="form-control" placeholder="Building">
															<option>CSB</option>
															<option>LATT</option>
															<option>Meliora</option>
														</select> <!-- End select -->										
													</div> <!-- End form-group -->
												</div> <!-- End column -->
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="room">Room</label>
														<select name="room" class="form-control" placeholder="Room">
															<option>306</option>
															<option>233</option>
															<option>255</option>
															<option>219</option>
															<option>393</option>
														</select> <!-- End select -->										
													</div> <!-- End form-group -->
												</div> <!-- End column -->							
											</div> <!-- End Row -->												
											<br>
											<div class="row">
												<div class="col-md-3">
													<button type = "submit"  name="submitButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-thumbs-up"></span> Create Account</button>
												</div> <!-- End column -->
											</div> <!-- End row -->	
										</fieldset> <!-- End fieldset -->
									</form> <!-- End form-horizontal -->
								</div> <!-- End panel-body -->
							</div> <!-- End panel panel-collapse -->
								<div class="panel-footer" id="createdProfessors">
									
								</div> <!-- End panel-footer -->	
						</div> <!-- End panel panel-success -->						
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