<?php  
    include('staffSession.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>New Term</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="fileinput.js"></script>
		<script src="newTerm.js"></script>
		
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $staff->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="staff.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li class="dropdown active">
										<a class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Manage<b class="caret"></b></a>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
											<li role="presentation" class="dropdown-header">Terms</li>
												<li><a href="newTerm.php">New Term</a></li>
												<li><a href="editTerm.php">Edit Term</a></li>
											<li role="presentation" class="divider"></li>
											<li role="presentation" class="dropdown-header">Professors</li>
												<li><a href="createProfessor.php">New Account</a></li>
												<li><a href="editProfessor.php">Edit Account</a></li>											
											<li role="presentation" class="divider"></li>
											<li role="presentation" class="dropdown-header">Students</li>
												<li><a href="reviewStudents.php">Review Students</a></li>	
												<li><a href="editStudent.php">Edit Account</a></li>																				  
										</ul>
									</li> <!-- End dropdown list item -->
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
							<p class="panelHeader">New Term</p>
						</div> <!-- End panel-heading -->
						<div class="panel-body">
							<div class="container">
								<div id="formBox">
									<form action="#" class="form-horizontal" id="newTermForm" method="post">
										<p class="optionHeader">Upload Term</p>
										<div class="row">
													<div class="col-md-12">
														<p>Use this form to upload a new term using a CSV file.
														Once you have uploaded the file, you can make <a href="editTerm.php">modifications</a> to the new term</p>			
													</div> <!-- End column -->						
										</div> <!-- End row -->								
										<div class="row">
											<div class="col-md-2">
												<div class="form-group">
													<label class="control-label" for="termYear">Term Year</label>
													<input type="text" id="termYear" name="termYear" class="form-control" />
												</div> <!-- End form-group -->
											</div> <!-- End column -->
											<div class="col-md-2">
												<div class="form-group">
													<label class="control-label" for="termSemester">Term Semester</label>
													<select id="termSemester" name="termSemester" class="form-control">
<?php
$termSemesters = Term::getAllTermSemesters();
foreach ($termSemesters as $termSemester) {
	echo "<option value=\"$termSemester\">".ucfirst($termSemester).'</option>';
}
?>
													</select> <!-- End select -->										
												</div> <!-- End form-group -->
											</div> <!-- End column -->
											<div class="col-md-2">
												<div class="form-group"> 
													<label class="control-label" for="termFile">Choose File</label><br>
													<input type="file" title="Browse" id="termFile" name="termFile" data-filename-placement="inside">													
												</div> <!-- End form-group -->							
											</div>	<!-- End column -->
											<div class="col-md-2">
												<div class="form-group">
													<button id="newTermButton" type="submit"  name="newTermButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-upload"></span> Upload Term</button>													
												</div> <!-- End form-group -->							
											</div>	<!-- End column -->												
										</div> <!-- End row -->																					
									</form> <!-- End form -->
								</div> <!-- end formbox -->
							</div> <!-- End container -->						
						</div> <!-- End panel-body -->
					</div> <!-- End panel panel-success -->						
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
