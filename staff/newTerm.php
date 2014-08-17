<?php  

require_once '../db.php';

$error = null;
$staff = null;
try {
	$staff = LoginSession::sessionContinue(STAFF);
} catch (TarsException $ex) {
	$error = $ex;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>New Term</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/bootstrap-fileinput.js"></script>
		<script src="../js/tars_utilities.js"></script>
		<script src="newTerm.js"></script>
		
	</head>
	<body>
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Manage
$header_active = 'manage';
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
				<div class="container">
					<div class="panel panel-success">
						<div class="panel-heading">
							<p class="panelHeader">New Term</p>
						</div> <!-- End panel-heading -->
						<div class="panel-body">
							<form enctype="multipart/form-data" action="../actions.php" class="form-horizontal" id="newTermForm" method="post">
								<input type="hidden" name="MAX_FILE_SIZE" value="4000000" />
								<p class="optionHeader">Upload Term</p>
								<div class="row">
									<div class="col-xs-12 col-sm-9 col-md-10">
										<p>
											Use this form to upload a new term using a CSV file.
											Once you have uploaded the file, you can make <a href="editTerm.php">modifications</a> to the new term
										</p>			
									</div> <!-- End column -->						
								</div> <!-- End row -->								
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-2">
										<label class="control-label" for="termYear">Term Year</label>
										<input type="text" id="termYear" name="termYear" class="form-control" />
									</div> <!-- End column -->
									<div class="col-xs-12 col-sm-6 col-md-2">
										<label class="control-label" for="termSemester">Term Semester</label>
										<select id="termSemester" name="termSemester" class="selectpicker form-control">
<?php
$termSemesters = Term::getAllTermSemesters();
foreach ($termSemesters as $termSemester) {
	echo "<option value=\"$termSemester\">".ucfirst($termSemester).'</option>';
}
?>
										</select> <!-- End select -->				
									</div> <!-- End column -->
									<div class="col-xs-12 col-sm-12 col-md-2">
										<label class="control-label" for="termFile">Choose File</label><br>
										<input type="file" title="Browse" id="termFile" name="termFile" data-filename-placement="inside">								
									</div>	<!-- End column -->
								</div> <!-- End row -->						
							</form> <!-- End form -->
						</div> <!-- End panel-body -->
						<div class="panel-footer">
							<button form="newTermForm" id="newTermButton" type="submit"  name="newTermButton" class="btn btn-success btn-block"><span class="glyphicon glyphicon-upload"></span> Upload Term</button>				
						</div>
					</div> <!-- End panel panel-success -->						
				</div> <!-- End container -->
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
