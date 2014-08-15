<?php

require_once '../db.php';

$error = null;
$professor = null;
try {
	$professor = Session::start(PROFESSOR);
} catch (TarsException $ex) {
	$error = $ex;
}

$term = null;
$pendingApps = 0;
if ($error != null) {
	try {
		$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
		if ($currentTermID != null) {
			$term = Term::getTermByID($currentTermID);
			/* Obtain the number of pending applications */
			$pendingApps = Application::findApplicationCount(null, null, $professor, $term, PENDING);
		}
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::USER_GET_APPLICATIONS, $ex);
	}
}
$unfilledPositions = false; /*TODO: Check for the existence of vacant positions */
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>Home</title>		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="professor.css" rel="stylesheet"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
	</head> 
	<body>
		<div id="page-wrapper">
<?php
// Display header for Home
$header_active = 'home';
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
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4 class="panel-title panelHeader">Home</h4>
						</div> <!-- End panel-heading -->
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<div class="row">
											<div class="col-xs-12">
												<h3>Announcements</h3>
											</div> <!-- End column -->
										</div> <!-- End row --> 								
									    <div class="row">
									    	<div class="col-xs-12">
													<p>Remember to submit reviews for your <a href="assistants.php">assistants</a> to help other
														professors make informed decisions.</p>							    			
									    	</div> <!-- End column -->
										</div> <!--End Row -->		
									</div> <!-- End column -->
									<div class="col-xs-6">
										<div class="row">
											<div class="col-xs-12">
												<h3>Notifications</h3>
											</div> <!-- End column -->
										</div> <!-- End row -->
										<?php
											if(!$professor->getSections()){
										?>
										<div class="row">
											<div class="col-xs-12">
													<div class="alert alert-danger" role="alert">
															You have not yet been assigned any sections. 
													</div> <!-- End alert alert-danger -->
											</div> <!-- End column -->									
										</div> <!-- End row -->
										<?php		
											}
											if($professor->getSections() && $unfilledPositions){
										?>
										<div class="row">
											<div class="col-xs-12">
													<div class="alert alert-danger" role="alert">
														There are still unfilled positions for some of your sections.<br>
														Review <a href="applicants.php">pending applications</a> to fill these positions.
													</div> <!-- End alert alert-danger -->
											</div> <!-- End column -->									
										</div> <!-- End row -->
										<?php		
											}
											if($professor->getSections() && !$unfilledPositions){
										?>
										<div class="row">
											<div class="col-xs-12">
													<div class="alert alert-info" role="alert">
															There are currently no notifications to display.
													</div> <!-- End alert alert-danger -->
											</div> <!-- End column -->									
										</div> <!-- End row -->		
										<?php		
											}
										?>									
										</div> <!-- End row -->											
									</div> <!-- End column -->									
								</div> <!-- End row -->																			
						</div> <!-- End panel-body -->
					</div> <!-- End panel panel-primary -->
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
</html>
	
