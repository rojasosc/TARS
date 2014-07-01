<?php  
require_once('staffSession.php');

$error = null;
$termID = isset($_GET['term']) ? $_GET['term'] : null;
$terms = array();
$thisTerm = null;
$assistants = array();
try {
	$terms = Term::getAllTerms();
	foreach ($terms as $term) {
		if ($termID != null && $term->getID() == $termID) {
			$thisTerm = $term;
		}
	}
	if ($thisTerm == null) {
		$thisTermID = Configuration::get(Configuration::CURRENT_TERM);
		if ($thisTermID != null) {
			$thisTerm = Term::getTermByID($thisTermID);
		}
	}
	$assistants = Application::getApplications(null, null, $thisTerm, APPROVED, 'pay');
} catch (PDOException $ex) {
	$error = new TarsException(Event::SERVER_DBERROR, Event::STAFF_GETPAYROLL, $ex);
}
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $staff->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="staff.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li class="dropdown">
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
									<li class="active"><a href="payroll.php"><span class="glyphicon glyphicon-usd"></span> Payroll</a></li>
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
				<div class="row">
					<?php if ($error != null) { echo $error->toHTML(); } ?>
					<div class="panel panel-success">
						<div class="panel-heading">
							<p class="panelHeader">Payroll</p>
						</div> <!-- End panel-heading -->
						<div class="panel-body">
							<div class="container" id="payrollContainer">
								<form class="form-horizontal" method="get" action="payroll.php" id="payrollForm">
									<legend>Select Term</legend>
									<div class="row">
										<div class="col-md-5">										
											<!--<label class="control-label" for="term">Term</label>-->
											<select id="term" name="term" class="form-control" placeholder="Term">
<?php
foreach ($terms as $term) {
	$sel = $term->getID() == $thisTerm->getID() ? ' selected="selected"' : '';
	echo '<option value="'.$term->getID().'"'.$sel.'>'.$term->getName().'</option';
}
?>
											</select> <!-- End select -->										
										</div> <!-- End column -->										
										<div class="col-md-5">
											<a class="btn btn-success btn-block" href="downloadPayroll.php" name="termSelectButton">View</a>
										</div> <!-- End col-md-5 -->
									</div> <!-- End row --->
									<br>
								</form> <!-- End term select form -->
								<form class="form-horizontal" method="post" action="fetchPayroll.php" id="payrollForm">
									<legend>Download Term</legend>
									<div class="row">
										<div class="col-md-10">
											<a class="btn btn-success btn-block" href="downloadPayroll.php" name="xlsButton"><span class="glyphicon glyphicon-download"></span> Download XLS File</a>
										</div> <!-- End col-md-3 -->
									</div> <!-- End row --> 											
								</form> <!-- End form -->
							</div>
							<div class="container" id="resultsContainer">
								<div class="row">
									<table class="table table-striped table-hover">
									<tr><th>University ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Course</th><th>Type</th><th>Class Year</th><th>Compensation</th></tr>
									<?php
									
									foreach($assistants as $assistant){
										$student = $assistant->getStudent();
										$position = $assistant->getPosition();
										$course = $position->getCourse();
									
									?>
									
									<tr>
										<td><?= $student->getUniversityID() ?></td> <td><?= $student->getFirstName() ?></td> <td><?= $student->getLastName() ?></td> <td><?= $student->getEmail() ?></td><td><?= $course->getCRN() ?></td><td><?= $position->getPositionType() ?></td><td><?= $student->getClassYear() ?></td>
										<td><?= $assistant->getCompensation() ?></td>
									</tr>
									<?php
									
									} /* Payroll closing brace */
									
									?>
									</table> <!-- End Table -->							
								</div> <!-- End row -->							
							</div>	<!-- End results container -->						
						</div> <!-- End panel-body -->
					</div> <!-- End panel panel-success -->
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
