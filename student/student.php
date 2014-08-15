<?php  

require_once '../db.php';

$error = null;
$student = null;
try {
	$student = Session::start(STUDENT);
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
		
		<title>TARS</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet"/>
		<link href="student.css" rel="stylesheet"/>

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
				<div id="alertHolder">
<?php
if ($error != null) { //Display any errors
	echo $error->toHTML();
}
?>
				</div>
<?php
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Student Home Page</h1>
					</div>
					<div class="panel-body">
						<div class="jumbotron">
							<h2>Welcome to TARS!</h2>
							<p>
								This is your home page, click on the tabs in the navigation bar above to go to more useful pages.
							</p>
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
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<!-- END Scripts -->
	</body>
</html>
