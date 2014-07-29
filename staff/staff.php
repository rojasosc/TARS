<?php  
require_once '../session.php';

$error = null;
$staff = null;
try {
	$staff = Session::start(STAFF);
} catch (TarsException $ex) {
	$error = $ex;
}

$term = null;
$totalUnverified = 0;
if ($error == null) {
	try {
		$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
		if ($currentTermID != null) {
			$term = Term::getTermByID($currentTermID);
			$totalUnverified = Application::findApplicationCount(null, null, null, $term, PENDING);
		}
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::USER_GET_APPLICATIONS, $ex);
	}
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>Home</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="staff.css" rel="stylesheet"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Home
$header_active = 'home';
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
													<p>Remember to review <a href="reviewStudents.php">pending applications</a> to assist 
														professors in making informed decisions.</p>							    			
									    	</div> <!-- End column -->
										</div> <!--End Row -->		
									</div> <!-- End column -->
									<div class="col-xs-6">
										<div class="row">
											<div class="col-xs-12">
												<h3>Notifications</h3>
											</div> <!-- End column -->
										</div> <!-- End row -->
										<div class="row">
											<div class="col-xs-12">
													<div class="alert alert-info" role="alert">
															There are currently no notifications to display.
													</div> <!-- End alert alert-danger -->
											</div> <!-- End column -->									
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</body>	
</html>
