<?php  
<<<<<<< HEAD:student/search.php
require_once 'studentSession.php';
require_once '../formInput.php';
require_once '../error.php';
=======
require_once '../studentSession.php';
<<<<<<< HEAD:student/search.php
require_once '/TARS/formInput.php';
require_once '/TARS/error.php';
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring:student/search/search.php
=======
require_once '../../formInput.php';
require_once '../../error.php';
>>>>>>> parent of f6dab39... refactored professor directory, tweaked student directory:student/search/search.php

$form_args = get_form_values(array('q','term','type'), false);
$pages = 7;

$positions = array();
$terms = array();
$positionTypes = array();
$currentTermID = null;
if ($error == null) {
	try {
		// get App CURRENT_TERM value
		$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
		// get all terms for the dropdown
		$terms = Term::getAllTerms();

		// get all the position types for the dropdown
		$positionTypes = Position::getAllPositionTypes(true);
		// add the "Any" type with value="0"
		$positionTypes[0] = 'Any Position Type';
		ksort($positionTypes);

		// get currently selected term, defaulting to CURRENT_TERM on not set
		if (!$form_args['term']) {
			$form_args['term'] = $currentTermID;
		}
		// get currently selected position, defaulting to "Any"
		if (!$form_args['type']) {
			$form_args['type'] = 0;
		}

		// find positions that match the fields
		$positions = Position::findPositions(
			$form_args['q'], $form_args['term'], $form_args['type'], $student->getID());
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::STUDENT_SEARCH, $ex);
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
		
		<!-- BEGIN CSS -->
<<<<<<< HEAD:student/search.php
<<<<<<< HEAD:student/search.php
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="student.css" rel="stylesheet">
=======
		<link href="/TARS/css/bootstrap.min.css" rel="stylesheet">
=======
		<link href="../../css/bootstrap.min.css" rel="stylesheet">
>>>>>>> parent of f6dab39... refactored professor directory, tweaked student directory:student/search/search.php
		<link href="../student.css" rel="stylesheet">
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring:student/search/search.php
		<link href="search.css" rel="stylesheet">
		<!-- END CSS -->
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<<<<<<< HEAD:student/search.php
<<<<<<< HEAD:student/search.php
		<script src="../js/bootstrap.min.js"></script>
=======
		<script src="/TARS/js/bootstrap.min.js"></script>
>>>>>>> parent of 39b2bbb... bug fixes to files in directory refactoring:student/search/search.php
=======
		<script src="../../js/bootstrap.min.js"></script>
>>>>>>> parent of f6dab39... refactored professor directory, tweaked student directory:student/search/search.php
		<script type="text/javascript" src="search.js"></script>
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
						<form role="form" method="post" id="application" action="appProcess.php">
							<div class="row" id="appAlertHolder"></div>
							<div class="row">
								<div class="col-xs-5 col-xs-offset-1">
									<label for="compensation">Compensation</label>
									<select name="compensation" class="form-control" id="compensation">
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
<?php
if ($error != null) {
	echo $error->toHTML();
}
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
										<input type="text" id="q" name="q" class="form-control" placeholder="Search..." value="<?=get_form_value('q', '', false)?>"/>
									</div>
									<div class="form-group">
										<label class="sr-only" for="term">Term</label>
										<select class="form-control" id="term" name="term">
										<?php
										foreach ($terms as $term_opt) {
										?>
											<option value="<?=$term_opt->getID()?>"<?php if(get_form_value('term', $currentTermID, false) == $term_opt->getID()){?> selected="selected"<?php }?>><?=$term_opt->getName()?></option>
										<?php
										}
										?>
										</select>
									</div>
									<div class="form-group">
										<label class="sr-only" for="type">Type</label>
										<select id="type" name="type" class="form-control">
										<?php
										foreach ($positionTypes as $index => $type_opt) {
										?>
											<option value="<?=$index?>"<?php if(get_form_value('type', 0, false) == $index){?> selected="selected"<?php }?>><?=$type_opt?></option>
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
								<ul class="pagination">
									<li><a href="#">&laquo;</a></li>
									<?php for($i= 1; $i <= $pages; $i++){?>
										<li><a href="#"><?=$i?></a></li>
									<?php }?>
									<li><a href="#">&raquo;</a></li>
								</ul>
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
									<tbody>
									<?php
										if($positions != false) {
											foreach($positions as $position) {
												$section = $position->getSection();
												$professors = $section->getAllProfessors();
												$professor = count($professors) > 0 ? implode(', ',
												array_map(function ($prof) {
													return $prof->getFILName();
												}, $professors)) : 'TBA';
												$sessions = $section->getAllSessions();
												$days = "";
												$time = "TBA";
												$place = "TBA";
												foreach($sessions as $session) {
													$days .= $session->getWeekdays();
													$time = $session->getStartTime()." - ".$session->getEndTime();
													$place = $session->getPlaceBuilding()." ".$session->getPlaceRoom();
												}
												if($days == "") {$days="TBA";}
									?>
											<tr>
												<td class="positionID hidden"><?=$position->getID()?></td>
												<td class="courseName"><?=$section->getCourseName()?></td>
												<td class="courseTitle hidden-xs hidden-sm"><?=$section->getCourseTitle()?></td>
												<td class="instructor"><?=$professor?></td>
												<td class="posType"><?=$position->getTypeTitle()?></td>
												<td class="days"><?=$days?></td>
												<td class="time"><?=$time?></td>
												<td class="place"><?=$place?></td>
												<td>
<?php
												if ($position->hasStudentApplied($student)) {
?>
													<button class="btn btn-default applyButton" disabled="disabled">Applied</button>
<?php
												} else {
?>
													<button class="btn btn-default applyButton" data-toggle="modal" data-target="#applyModal"><span class="glyphicon glyphicon-pencil"></span> Apply</button>
<?php
												}
?>
												</td>
											</tr>
									<?php
											}
										} else {
									?>
											<tr>
												<td colspan="7">No results</td>
											</tr>
									<?php
										}
?>
									</tbody>
								</table>
								<!-- END Results Table -->
								<!-- BEGIN Pagination -->
								<ul class="pagination">
									<li><a href="#">&laquo;</a></li>
									<?php for($i= 1; $i <= $pages; $i++){?>
										<li><a href="#"><?=$i?></a></li>
									<?php }?>
									<li><a href="#">&raquo;</a></li>
								</ul>
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
