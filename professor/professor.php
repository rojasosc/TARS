<?php
require_once 'professorSession.php';

$term = null;
$pendingApps = 0;
if ($error != null) {
	try {
		$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
		if ($currentTermID != null) {
			$term = Term::getTermByID($currentTermID);
			/* Obtain the number of pending applications */
			$pendingApps = Application::getApplicationCount(null, $professor, $term, PENDING);
		}
	} catch (PDOException $ex) {
		$error = new TarsException(Event::SERVER_DBERROR, Event::USER_GET_APPLICATIONS, $ex);
	}
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<title>Home</title>		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="professor.css" rel="stylesheet">
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
<?php
if ($error != null) {
	echo $error->toHTML();
}
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
			    <div class="row">
					<div class="container">
						<div class="jumbotron">
							<h2>Welcome Professor <?= $professor->getLastName() ?>!</h2>
							
							<h3>Notifications</h3> 
							<p> You have <?= $pendingApps ?> <a href="applicants.php" >applications</a> pending!</p> 
							
							<h3>Announcements</h3>
							<p>Remember to submit feedback for your assistants by (date).</p>
							<p>Your feedback helps rank assistants by their past experience.</p>
						</div> <!-- End jumbotron -->
					</div> <!-- End container -->
				</div> <!--End Row -->
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
	
