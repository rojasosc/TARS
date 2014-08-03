<?php  
require_once '../session.php';
require_once '../actions.php';
require_once '../error.php';

$error = null;
$student = null;
$terms = array();
$currentTermID = null;
$positionTypes = array();
try {
	$student = Session::start(STUDENT);
	// get all terms for the dropdown
	$terms = Term::getAllTerms();
	// get App CURRENT_TERM value
	$currentTermID = Configuration::get(Configuration::CURRENT_TERM);

	// get all the position types for the dropdown
	$positionTypes = Position::getAllPositionTypes(true);
	// add the "Any" type with value="0"
	$positionTypes[0] = 'Any Position Type';
	ksort($positionTypes);
} catch (TarsException $ex) {
	$error = $ex;
} catch (PDOException $ex) {
	$error = new TarsException(Event::SERVER_DBERROR, Event::STUDENT_SEARCH, $ex);
}

?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>TARS</title>
		
		<!-- BEGIN CSS -->
		<link href="../css/bootstrap-select.min.css" rel="stylesheet"/>
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="student.css" rel="stylesheet"/>
		<link href="search.css" rel="stylesheet"/>
		<!-- END CSS -->
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap-select.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/tars_utilities.js"></script>
		<script src="search.js"></script>
		<!-- END Scripts -->
	</head>
  
	<body>
		<!-- BEGIN Info Modal -->
		<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Position Types Details</h1>
					</div>
					<div class="modal-body">
						<h2>Workshop Leader</h2>
						<h3>Responsibilities</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Times</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Compensation</h3>
						<p>
							Stuffity stuff
						</p>
						<hr/>
						<h2>Lab TA</h2>
						<h3>Responsibilities</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Times</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Compensation</h3>
						<p>
							Stuffity stuff
						</p>
						<hr/>
						<h2>Grader</h2>
						<h3>Responsibilities</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Times</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Compensation</h3>
						<p>
							Stuffity stuff
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Info Modal -->
		<!-- BEGIN Apply Modal -->
		<div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Application</h1>
					</div>
					<div class="modal-body">
						<div id="jobDetails">
						</div>
						<form role="form" method="post" id="application" action="#">
							<div class="row" id="appAlertHolder"></div>
							<div class="row">
								<div class="col-xs-5 col-xs-offset-1">
									<label for="compensation">Compensation</label>
									<select name="compensation" class="selectpicker form-control" id="compensation">
										<option value="pay">Pay</option>
										<option value="credit">Credit</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-10 col-xs-offset-1">
									<label for="qualifications">Qualifications <small><p>
										Please explain below why you want to fill this position and why you are qualified to. Please keep it clear and concise.
									</p></small></label>
									<textarea class="form-control" rows="4" cols="64" name="qualifications" id="qualifications"></textarea>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="application" value="Submit" id="appSubmit">Submit</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Apply Modal -->
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Home
$header_active = 'search';
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
				<!-- BEGIN Position Search -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Position Search</h1>
					</div>
					<div class="panel-body">
						<div class="container-fluid display-area">
							<!-- BEGIN Search Form -->
							<form role="form" action="search.php" method="get" id="searchForm">
								<div class="form-inline" id="inputrow">
									<div class="form-group">
										<label class="sr-only" for="q">Search</label>
										<input type="text" id="q" name="q" class="form-control" placeholder="Search..." value=""/>
									</div>
									<div class="form-group">
										<label class="sr-only" for="term">Term</label>
										<select class="selectpicker form-control" id="term" name="term">
										<?php
										foreach ($terms as $term_opt) {
										?>
											<option value="<?=$term_opt->getID()?>"><?=$term_opt->getName()?></option>
										<?php
										}
										?>
										</select>
									</div>
									<div class="form-group">
										<label class="sr-only" for="type">Type</label>
										<select id="type" name="type" class="selectpicker form-control">
										<?php
										foreach ($positionTypes as $index => $type_opt) {
										?>
											<option value="<?=$index?>"><?=$type_opt?></option>
										<?php
										}
										?>
										</select>
									</div>
									<input type="submit" value="Search" class="btn btn-primary"/>
								</div>
							</form>
							<!-- END Search Form -->
							<hr/>
							<!-- BEGIN Search Results -->
							<div id="search-results">
								<!-- BEGIN Pagination -->
								<ul class="pagination"></ul>
								<!-- END Pagination -->
								<!-- BEGIN Results Table -->
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="hidden">#</th>
											<th>Course Number</th>
											<th class="hidden-xs hidden-sm">Course Title</th>
											<th>Professor</th>
											<th>Position <button class="btn btn-sm btn-default pull-right" data-target="#infoModal" data-toggle="modal"><span class="glyphicon glyphicon-info-sign"></span></button><br/>Type</th>
											<th>Day</th>
											<th>Time</th>
											<th>Place</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="results">
									</tbody>
								</table>
								<!-- END Results Table -->
								<!-- BEGIN Pagination -->
								<ul class="pagination"></ul>
								<!-- END Pagination -->
							</div>
							<!-- END Search Results -->
						</div>
					</div>
				</div>
				<!-- END Position Search -->
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
