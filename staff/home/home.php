<?php  
require_once '../staffSession.php';

$term = null;
$totalUnverified = 0;
if ($error == null) {
	try {
		$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
		if ($currentTermID != null) {
			$term = Term::getTermByID($currentTermID);
			$totalUnverified = Application::getApplicationCount(null, null, $term, PENDING);
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
		
		<link href="../../css/bootstrap.min.css" rel="stylesheet">
		<link href="../staff.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../../js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- BEGIN page-wrapper -->
            
		<div id="page-wrapper">
<?php
// Display header for Home
$header_active = 'home';
require '../header.php';
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
					<div class="jumbotron">
						<h2 class="welcome">Welcome <?= $staff->getFirstName() ?>!</h2>					
						<h3><span class="glyphicon glyphicon-warning-sign"></span> Notifications</h3> 
							<p>You have <?= $totalUnverified ?> <a href="../manage/students/reviewStudents.php">students</a> that need to be verified.</p>
							<p>(name) has dropped out of his assistantship.</p>
							<p>(name) has replaced (name) in (course) as a (type).</p>
					</div> <!-- End jumbotron -->
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
