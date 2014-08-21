<?php  

require_once '../db.php';

$error = null;
$staff = null;
try {
	$staff = LoginSession::sessionContinue(ADMIN);
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
		
		<title>Edit Users</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>	
		<script src="../js/tars_utilities.js"></script>
	</head>
	<body>
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Manage
$header_active = 'profile';
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
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Events</h1>
					</div> <!-- End panel-heading -->
					<div class="panel-body">
						<form class="form-horizontal filter-events-form" novalidate="novalidate" role="form" id="eventFilterForm">
							<div class="row">
								<div class="col-xs-4">
									<label class="control-label" for="user">User Name or Email</label>
									<input id="emailSearch" type="text" class="form-control" name="user" placeholder="User">
								</div> <!-- End column -->
							</div> <!-- End row -->
							<br/>
							<div class="row">
								<div class="col-xs-4">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-primary active">
											<input type="checkbox" value="crit" name="sevCrit" checked> Critical
										</label>
										<label class="btn btn-primary active">
											<input type="checkbox" value="error" name="sevError" checked> Error
										</label>
										<label class="btn btn-primary active">
											<input type="checkbox" value="notice" name="sevNotice" checked> Notice
										</label>
										<label class="btn btn-primary active">
											<input type="checkbox" value="info" name="sevInfo" checked> Info
										</label>
										<label class="btn btn-primary">
											<input type="checkbox" value="debug" name="sevDebug"> Debug
										</label>
									</div>
								</div> <!-- End column -->
								<div class="col-xs-4">
									<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Filter</button>
								</div> <!-- End column -->
							</div> <!-- End row -->
						</form> <!-- End form -->
						<br/>
						<!-- Begin Pagination -->
						<ul class="pagination"></ul>
						<!-- End Pagination -->
						<div class="row">
							<div class="col-md-12">
								<table class="table table-condensed table-striped user-search-table" id="results">
									<thead>
										<tr>
											<th class="hidden">ID</th><th class="col-xs-3 col-md-1">Time</th><th class="col-xs-3 col-md-1">User</th><th class="col-xs-3 col-md-1">User IP</th><th class="col-xs-3 col-md-1">Event Type</th><th class="col-xs-hidden col-md-4">Event Description</th><th class="col-xs-hidden col-md-1">Referenced Object</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table> <!-- End table table-striped -->
							</div> <!-- End column -->
						</div> <!-- End row -->
						<!-- Begin Pagination -->
						<ul class="pagination"></ul>
						<!-- End Pagination -->
					</div> <!-- End panel-body -->
					<div class="panel-footer">
					</div> <!-- End panel-footer -->
				</div> <!-- End panel panel-primary -->
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
