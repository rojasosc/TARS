<?php  
    include('staffSession.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Modify Professor</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<link rel="stylesheet" href="../bootstrapValidator.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../bootstrapValidator.min.js"></script>		
		<script type="text/javascript" src="findProfessorAccount.js"></script>
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
											<li><a href="modifyTerm.php">Modify Term</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageProfessors.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Professors<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="createProfessor.php">New Account</a></li>
											<li><a href="modifyProfessor.php">Modify Account</a></li>
										</ul> <!-- End drop down unordered list -->
									</li> <!-- End drop down list item -->
									<li class="dropdown">
										<a href="manageAssistants.php" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage Assistants<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="modifyStudent.php">Modify Account</a></li>
											<li><a href="verifyStudents.php">Screen Students</a></li>
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
				<div class="container">
						<div class="panel panel-success">
							<div class="panel-heading">
								<h4>Search Professors</h4>
								
							</div> <!-- End panel-heading -->
								<div class="panel-body">
									<form class="form-horizontal" id="findAccountForm" method="post" action="findProfessorAccount.php">
										<fieldset>
											
											<small>Enter an email address to find an existing account.</small>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="emailSearch">Email</label>
														<input id="emailSearch" type="email" class="form-control" name="emailSearch" placeholder="Email"/>					
													</div> <!-- End form-group -->							
												</div> <!-- End column -->					
											</div> <!-- End row -->
											<br>
											<div class="row">
												<div class="col-md-3">
													<button id="submitButton"  name="submitButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-thumbs-up"></span> Search</button>
												</div> <!-- End column -->
											</div> <!-- End row -->	
										</fieldset> <!-- End fieldset -->
									</form> <!-- End form-horizontal -->
								</div> <!-- End panel-body -->
						</div> <!-- End panel panel-success -->						
				</div> <!-- End container -->
				
				<div class="container">
						<div class="panel panel-danger">
							<div class="panel-heading">
								<h4>Results</h4>
							</div> <!-- End panel-heading -->
								<div class="panel-body" id="results">
								        <div class="jumbotron" id="noResults">
										<p id="noResults">No Professors Found.</p>
								        </div> <!-- End no results div -->								
									<div class="jumbotron" id="formBox">
										<form action="updateProfessorProcess.php" class="form-horizontal" id="updateForm" method="post">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group"> 
														<label class="control-label" for="firstName">First Name</label>
														<input id="firstName" type="text" class="form-control" name="firstName"/>														
													</div> <!-- End form-group -->							
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="firstName">Last Name</label>
														<input id="lastName" type="text" class="form-control" name="lastName" />													
													</div> <!-- End form-group -->							
												</div>							
											</div> <!-- End row -->
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="email">Email</label>
														<input id="email" type="email" class="form-control" name="email"/>					
													</div> <!-- End form-group -->							
												</div>	<!-- End column -->						
											</div> <!-- End row -->
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="officePhone">Office Phone</label>
														<input id="officePhone" type="tel" class="form-control" name="officePhone"/>
													</div> <!-- End form-group -->
												</div> <!-- End column -->
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label" for="mobilePhone">Mobile Phone</label>
														<input id="mobilePhone" type="tel" class="form-control" name="mobilePhone" />
													</div> <!-- End form-group -->
												</div> <!-- End column -->								
											</div> <!-- End row -->
											<br>
											<div class="row">
												<div class="col-md-3">
													<button id="updateButton" type="submit"  name="updateButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-thumbs-up"></span> Update</button>
												</div> <!-- End column -->
												<div class="col-md-3">
													<button id="closeForm" name="closeForm" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-thumbs-down"></span> Close</button>
												</div> <!-- End column -->												
											</div> <!-- End row -->								
										</form> <!-- End form -->
									</div> <!-- End jumbotron -->
								</div> <!-- End panel-body -->
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</body>	
</html>