<?php  
require_once '../session.php';

$error = null;
$staff = null;
try {
	$staff = Session::start(STAFF);
} catch (TarsException $ex) {
	$error = $ex;
}

$termID = isset($_GET['term']) ? $_GET['term'] : null;
$terms = array();
$thisTerm = null;
$assistants = array();
if ($error == null) {
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
		$error = new TarsException(Event::SERVER_DBERROR, Event::STAFF_GET_PAYROLL, $ex);
	}
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Payroll</title>
		
		<link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="../css/bootstrap-select.min.css" rel="stylesheet">
		<link type="text/css" href="staff.css" rel="stylesheet">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
		<script type="text/javascript" src="../js/tars_utilities.js"></script>

	</head>
	<body>
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Manage
$header_active = 'payroll';
require 'header.php';
?>
			<!-- BEGIN Page Content -->
			<div id="content">
				<div id="alertHolder">
<?php
if ($error != null) {
	echo $error->toHTML();
}
?>
				</div>
<?php
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
				<div class="row">
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
											<select id="term" name="term" class="selectpicker form-control" placeholder="Term">
<?php
foreach ($terms as $term) {
	$sel = ($thisTerm != null && $term->getID() == $thisTerm->getID()) ? ' selected="selected"' : '';
	echo '<option value="'.$term->getID().'"'.$sel.'>'.$term->getName().'</option>';
}
?>
											</select> <!-- End select -->										
										</div> <!-- End column -->										
										<div class="col-md-5">
											<a class="btn btn-success btn-block" name="termSelectButton">View</a>
										</div> <!-- End col-md-5 -->
									</div> <!-- End row -->
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
										$student = $assistant->getCreator();
										$position = $assistant->getPosition();
										$section = $position->getSection();
										$posTypeID = $position->getType();
										$posType;
										switch($posTypeID) {
											case 1: $posType = "Lab TA";
											break;
											case 2: $posType = "Workshop Leader";
											break;
											case 3: $posType = "Super Leader";
											break;
											case 4: $posType = "Grader";
											break;
											default: $posType = "Invalid Position Type";
											break;
										}
									?>
									
									<tr>
										<td><?= $student->getUniversityID() ?></td> <td><?= $student->getFirstName() ?></td> <td><?= $student->getLastName() ?></td> <td><?= $student->getEmail() ?></td><td><?= $section->getCourseName() ?></td><td><?= $position->getTypeTitle() ?></td><td><?= $student->getClassYear() ?></td>
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
<?php
}
?>
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
