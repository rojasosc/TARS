<?php
require_once '../session.php';

$error = null;
$staff = null;
try {
	$staff = Session::start(STAFF);
	$sections = Section::getAllSections();
} catch (TarsException $ex) {
	$error = $ex;
}
//TODO: Display everything via tables, be sure to include hidden data in each row.
//TODO: Create a modal for editing purposes
//TODO: Functional paginated search [Do this one first, lelz]
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>Edit Term</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<link href="editTermUI.css" rel="stylesheet"/>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/tars_utilities.js"></script>
		<script type="text/javascript" src="editTermUI.js"></script>
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
				<div class="alert alert-warning hidden-lg" role="alert">
					<p>
						<strong>Warning!</strong> This page is meant only to be viewed on a computer screen.
					</p>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Edit Term</h1>
					</div>
					<div class="panel-body">
						<form class="form-horizontal fetch-sections-form" role="form" id="fetchSectionsForm">
							<fieldset>
								<legend>Filter by:</legend>
								<div class="row">
									<div class="col-xs-6 col-sm-4 col-md-3">
										<label class="control-label" for="CRNFilter">CRN:</label>
										<input id="CRNFilter" name="CRNFilter" type="text" class="form-control" placeholder="e.g. 12345">
									</div>
									<div class="col-xs-6 col-sm-4 col-md-3">
										<label class="control-label" for="courseFilter">Course:</label>
										<input id="courseFilter" name="courseFilter" type="text" class="form-control" placeholder="e.g. CSC 171">
									</div>
									<div class="col-xs-6 col-sm-4 col-md-3">
										<label class="control-label" for="typeFilter">Type: </label>
										<input id="typeFilter" name="typeFilter" type="text" class="form-control" placeholder="e.g. lab, lecture">
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-4">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-primary active">
												<input type="radio" value="all" name="all" checked> All
											</label>
											<label class="btn btn-primary">
												<input type="radio" value="ok" name="ok"> OK
											</label>
											<label class="btn btn-primary">
												<input type="radio" value="not-ok" name="notOk"> Not OK
											</label>
										</div>
									</div>
									<div class="col-xs-3">
										<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</div>
								</div>
							</fieldset>
						</form>
						<hr />
						<ul class="pagination">
						</ul>
						<table class="table table-striped table-condensed">
							<thead>
								<tr>
									<th>Course</th>
									<th>Type</th>
									<th>CRN</th>
									<th>Day</th>
									<th>Time</th>
									<th>Place</th>
									<th>Lab TAs</th>
									<th>WS Leaders</th>
									<th>Super Leaders</th>
									<th>Lec TAs</th>
									<th>Graders</th>
									<th>Edit</th>
								</tr>
							</thead>
							<tbody id="results">
							</tbody>
						</table>
						<ul class="pagination">
						</ul>
						<?php
	foreach($sections as $section) {
		$sessions = $section->getAllSessions();
		$sessions = SectionSession::combineSessions($sessions);
		$profs = $section->getAllProfessors();
		//	print_r($section);
		//	print_r($sessions);
		//	print_r($profs);
		$labTACount = $section->getTotalPositionsByType($profs[0], 1);
		if(!$labTACount) {
			$labTACount = 0;
		}
		$wsTACount = $section->getTotalPositionsByType($profs[0], 2);
		if(!$wsTACount) {
			$wsTACount = 0;
		}
		$wsslCount = $section->getTotalPositionsByType($profs[0], 3);
		if(!$wsslCount) {
			$wsslCount = 0;
		}
		$lecTACount = $section->getTotalPositionsByType($profs[0], 5);
		if(!$lecTACount) {
			$lecTACount = 0;
		}
		$graderCount = $section->getTotalPositionsByType($profs[0], 4);
		if(!$graderCount) {
			$graderCount = 0;
		}
		// TODO normalize (multiple professors here would require string parsing to find separate email addresses)
		$profName = implode(', ', array_map(function ($prof) { return $prof->getEmail(); }, $profs));
		
		// TODO normalize (multiple sessions at different times cannot be put)
		if(count($sessions) != 0) {
			$session = $sessions[0];
		} else {
			$session = SectionSession::emptySession();
		}
						?>
						
						<div class="panel panel-info coursePanel">
							<div class="panel-heading">
								<h2 class="panel-title" data-toggle="collapse" data-target="#<?=$section->getCRN()?>Panel"><?='['.$section->getSectionType().'] '.$section->getCourseDepartment().' '.$section->getCourseNumber()?><span class="hidden-xs"><?=': '.$section->getCourseTitle()?></span></h2>
							</div>
							<div class="panel-collapse collapse in sectionPanel" id="<?=$section->getCRN()?>Panel">
								<div class="panel-body" >
									<div class="container-fluid">
										<form role="form" action="#" method="post" id="<?=$section->getCRN()?>Form" data-sectionType="<?=$section->getSectionType()?>">
											<div class="row">
												<h3>Course Info</h3><br />
												<div class="col-xs-6 col-sm-2">
													CRN: <input type="text" class="form-control CRN" value="<?=$section->getCRN()?>"/>
												</div>
												<div class="col-xs-6 col-sm-2">
													Course #: <input type="text" class="form-control courseNum" value="<?=$section->getCourseNumber()?>"/> 
												</div>
												<div class="col-xs-12 col-sm-4">
													Course Title: <input type="text" class="form-control courseTitle" value="<?=$section->getCourseTitle()?>"/>
												</div>
												<div class="col-xs-6 col-sm-2">
													Building: <input type="text" class="form-control building" value="<?=$session->getPlaceBuilding()?>"/>
												</div>
												<div class="col-xs-6 col-sm-2">
													Room: <input type="text" class="form-control room" value="<?=$session->getPlaceRoom()?>"/>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12 col-sm-4">
													Instructor: <input type="text" class="form-control instructor" value="<?=$profName?>"/>
												</div>
												<div class="col-xs-4 col-sm-2">
													Day: <input type="text" class="form-control day" value="<?=$session->getWeekdays()?>"/>
												</div>
												<div class="col-xs-4 col-sm-2">
													Start: <input type="text" class="form-control startTime" value="<?=$session->getStartTime()?>"/>
												</div>
												<div class="col-xs-4 col-sm-2">
													End: <input type="text" class="form-control endTime" value="<?=$session->getEndTime()?>"/>	
												</div>
											</div>
											<div class="row">
												<h3>TA Counts</h3><br />
												<div class="col-xs-2">
													Lab: <input type="text" class="form-control labTACount" value="<?=$labTACount?>"/>
												</div>
												<div class="col-xs-2">
													W<span class="hidden-xs hidden-sm">o</span>rksh<span class="hidden-xs hidden-sm">o</span>p: <input type="text" class="form-control wsTACount" value="<?=$wsTACount?>"/>
												</div>
												<div class="col-xs-2">
													Super <span class="hidden-xs hidden-sm">Leader</span>: <input type="text" class="form-control wsslCount" value="<?=$wsslCount?>"/>
												</div>
												<div class="col-xs-2">
													Lecture: <input type="text" class="form-control lecTACount" value="<?=$lecTACount?>"/>
												</div>
												<div class="col-xs-2">
													Grader: <input type="text" class="form-control graderCount" value="<?=$graderCount?>"/>
												</div>
											</div> <br/>
											<div class="row">
												<div class="col-xs-4 col-sm-3 col-md-2">
													<button type="submit" value="Submit" class="form-control btn btn-success">Save</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<?php
	}
						?>
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
