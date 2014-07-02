<?php  
require_once 'studentSession.php';
require_once '../formInput.php';
require_once '../error.php';

$form_args = get_form_values(array('search','term','type'));
$pages = 7;

$positions = array();
$terms = array();
$error = null;
try {
	$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
	if (!$form_args['term']) {
		$form_args['term'] = $currentTermID;
	}
	if (!$form_args['type']) {
		$form_args['type'] = -1; // "Any"
	}
	$positions = Position::findPositions(
		$form_args['search'], $form_args['term'], $form_args['type'], $student->getID());
	$terms = Term::getAllTerms();
} catch (PDOException $ex) {
	$error = new TarsException(Event::SERVER_DBERROR, Event::SEARCH_POSITIONS, $ex);
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
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="student.css" rel="stylesheet">
		<link href="search.css" rel="stylesheet">
		<!-- END CSS -->
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
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
						<form role="form" method="post" id="application" action="appProcess.php">
							<div class="row">
								<div class="col-xs-5 col-xs-offset-1">
									<input type="hidden" value="<?= $student->getID()?>" id="studentID"/>
									<label>
										Compensation:
										<select name="compensation" class="form-control" id="compensation">
											<option value="pay">Pay</option>
											<option value="credit">Credit</option>
										</select>
									</label>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-10 col-xs-offset-1">
									<label>
										<p>
											Please explain below why you want to fill this position and why you are qualified to. Please keep it clear and concise.
										</p>
										<textarea class="form-control" rows="4" cols="64" name="qualifications" id="qualifications"></textarea>
									</label>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" id="appClose">Close</button>
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
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Position Search</h1>
					</div>
					<div class="panel-body">
						<div class="container-fluid display-area">
							<form role="form" action="search.php" method="post" id="searchForm">
								<div class="row" id="inputrow">
									<div class="col-xs-12 col-sm-6">
										Search:
										<input type="text" name="search" class="form-control" placeholder="Search..." value="<?=get_form_value('search')?>"/>
									</div>
									<div class="col-xs-6 col-sm-3">
										Term:
										<select class="form-control" name="term">
										<?php
										foreach ($terms as $term_opt) {
										?>
											<option value="<?=$term_opt->getID()?>"<?php if(get_form_value('term', CURRENT_TERM) == $term_opt->getID()){?> selected="selected"<?php }?>><?=$term_opt->toString()?></option>
										<?php
										}
										?>
										</select>
									</div>
									<div class="col-xs-6 col-sm-3">
										Type:
										<select name="type" class="form-control">
										<?php
										$type_opts = array(-1 => 'Any', 1 => 'Workshop Leader', 2 => 'Lab TA', 3 => 'Grader');
										foreach ($type_opts as $index => $type_opt) {
										?>
											<option value="<?=$index?>"<?php if(get_form_value('type', -1) == $index){?> selected="selected"<?php }?>><?=$type_opt?></option>
										<?php
										}
										?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-2 col-xs-offset-5">
										<input type="submit" value="Search" class="btn btn-primary"/>
									</div>
								</div>
							</form>				
							<hr/>
							<div id="search-results">
								<ul class="pagination">
									<li><a href="#">&laquo;</a></li>
									<?php for($i= 1; $i <= $pages; $i++){?>
										<li><a href="#"><?=$i?></a></li>
									<?php }?>
									<li><a href="#">&raquo;</a></li>
								</ul>
								<table class="table table-striped">
									<tr>
										<th class="hidden-xs hidden-sm">Position No.</th>
										<th>Course Number</th>
										<th class="hidden-xs">Course</th>
										<th>Professor</th>
										<th>Position Type <br/><button class="btn btn-default" data-target="#infoModal" data-toggle="modal"><span class="glyphicon glyphicon-info-sign"></span></button></th>
										<th>Time</th>
										<th></th>
									</tr>
									<?php
										if($positions != false) {
											foreach($positions as $position) {
												$course = $position->getCourse();
												$professor = $position->getProfessor();
									?>
											<tr>
												<td class="positionID hidden-xs hidden-sm"><?=$position->getID()?></td>
												<td><?=$course->getDepartment()?><?=$course->getNumber()?></td>
												<td class="hidden-xs"><?=$course->getTitle()?></td>
												<td><?=$professor->getFILName()?></td>
												<td><?=$position->getPositionType()?></td>
												<td><?=$position->getTime()?></td>
												<td>
													<button class="btn btn-default applyButton" data-toggle="modal" data-target="#applyModal"><span class="glyphicon glyphicon-pencil"></span> Apply</button>
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
								</table>
								<ul class="pagination">
									<li><a href="#">&laquo;</a></li>
									<?php for($i= 1; $i <= $pages; $i++){?>
										<li><a href="#"><?=$i?></a></li>
									<?php }?>
									<li><a href="#">&raquo;</a></li>
								</ul>
							</div>
						</div>
					</div>
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
