<?php  
    include('staffSession.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Payroll</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>

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
					<div class="jumbotron" >
						<form class="form-horizontal" method="post" action="fetchPayroll.php" id="payrollForm">
							<fieldset>
								<legend>Select Term</legend>
								<div class="row">
										<div class="col-md-4">
											<div class="form-group">
											<label class="control-label" for="term">Term</label>
											<select name="term" class="form-control" placeholder="Term">
												<!-- Still need to use PHP to render these dynamically -->
												<option>Fall-2013</option>
												<option>Spring-2014</option>
												<option>Summer-2014</option>
												<option>Fall-2014</option>
												<option>Spring-2015</option>
											</select> <!-- End select -->										
											</div> <!-- End column -->									
										</div> <!-- End form-group -->
								</div> <!-- End row --->
							</fieldset>
						</form> <!-- End form -->
						<div class="row">
							<table class="table table-striped">
							<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th>Type</th><th>Class Year</th><th>Compensation</th></tr>
							<!-- Note: use Ajax to render table content -->

							<?php
							
							$term = "Fall-2014";
							$assistants = getPayrollByTerm($term);
							
							foreach($assistants as $assistant){
							
							?>
							
							<tr>
								<td><?= $assistant['studentID'] ?></td> <td><?= $assistant['firstName'] ?></td> <td><?= $assistant['lastName'] ?></td> <td><?= $assistant['email'] ?></td><td><?= $assistant['courseNumber'] ?></td><td><?= $assistant['type'] ?></td><td><?= $assistant['classYear'] ?></td>
								<td><?= $assistant['compensation'] ?></td>
							</tr>
							<?php
							
							} /* Payroll closing brace */
							
							?>
							
							</table> <!-- End Table -->							
						</div>
					</div> <!-- End jumbotron -->
				<div class="row">
					<div class="col-md-3">
						<a class="btn btn-success btn-block" href="downloadPayroll.php" name="xlsButton"><span class="glyphicon glyphicon-download"></span> Download XLS File</a>
					</div> <!-- End col-md-3 -->
				</div> <!-- End row -->	
				<br>
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