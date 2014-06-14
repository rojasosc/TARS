<?php  
include('studentSession.php');
include('../formInput.php');

$form_args = get_form_values(array('search','term','type'));

try {
	if (!$form_args['term']) {
		$form_args['term'] = CURRENT_TERM;
	}
	if (!$form_args['type']) {
		$form_args['type'] = -1; // "Any"
	}
	$positions = Position::findPositions(
		$form_args['search'], $form_args['term'], $form_args['type']);
} catch (PDOException $ex) {
	Error::setError(Error::EXCEPTION, 'Error getting position list.', $ex);
	$positions = array();
}

try {
	$terms = Term::getAllTerms();
} catch (PDOException $ex) {
	Error::setError(Error::EXCEPTION, 'Error getting term list.', $ex);
	$terms = array();
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
		<div class="modal fade" id="infomodal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
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
		<div class="modal fade" id="applymodal" tabindex="-1" role="dialog" aria-labelledby="applyModalLabel" aria-hidden="true">
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $brand ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="student.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li><a href="cur_pos.php"><span class="glyphicon glyphicon-th-list"></span> Current Positions</a></li>
									<li class="active"><a href="search.php"><span class="glyphicon glyphicon-inbox"></span> Position Search</a></li>
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
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Position Search</h1>
					</div>
					<div class="panel-body">
						<div class="container-fluid display-area">
							<form role="form" action="search.php" method="post" id="searchForm">
								<div class="row" id="inputrow">
									<div class="col-xs-6">
										Search:
										<input type="text" name="search" class="form-control" placeholder="Search..." value="<?=get_form_value('search')?>"/>
									</div>
									<div class="col-xs-3">
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
									<div class="col-xs-3">
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
								<table class="table table-striped">
									<tr>
										<th>Position No.</th>
										<th>Course Number</th>
										<th>Course</th>
										<th>Professor</th>
										<th>Position Type <button class="btn btn-default" data-target="#infomodal" data-toggle="modal"><span class="glyphicon glyphicon-info-sign"></span></button></th>
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
												<td class="positionID"><?=$position->getID()?></td>
												<td><?=$course->getDepartment()?><?=$course->getNumber()?></td>
												<td><?=$course->getTitle()?></td>
												<td><?=$professor->getFILName()?></td>
												<td><?=$position->getPositionType()?></td>
												<td><?=$position->getTime()?></td>
												<td>
													<button class="btn btn-default applyButton" data-toggle="modal" data-target="#applymodal"><span class="glyphicon glyphicon-pencil"></span> Apply</button>
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
							</div>
						</div>
					</div>
				</div>
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
