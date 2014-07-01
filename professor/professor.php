<?php
require_once('professorSession.php');

$pendingApps = 0;
$error = null;
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
								<a class="navbar-brand" href="editProfile.php"><span class="glyphicon glyphicon-user"></span> <?= $professor->getFILName() ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li class="active"><a href="professor.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li><a href="assistants.php"><span class="glyphicon glyphicon-th-list"></span> Assistants</a></li>
									<li><a href="applicants.php"><span class="glyphicon glyphicon-inbox"></span> Applicants</a></li>
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
			    <div class="row">
					<div class="container">
						<div class="jumbotron">
							<?php if ($error != null) {	echo $error->getHTML(); } ?>
							<h2>Welcome Professor <?= $professor->getLastName() ?>!</h2>
							
							<h3>Notifications</h3> 
							<p> You have <?= $pendingApps ?> <a href="applicants.php" >applications</a> pending!</p> 
							
							<h3>Announcements</h3>
							<p>Remember to submit feedback for your assistants by (date).</p>
							<p>Your feedback helps rank assistants by their past experience.</p>
						</div> <!-- End jumbotron -->
					</div> <!-- End container -->
			    </div> <!--End Row -->			    
			</div>
			<!-- END Page Content --> 	    
			<!--BEGIN Page Footer -->
			<div id="footer">
			</div>
			<!--END Page Footer -->
	
		</div> 
		<!-- End page-wrapper -->
</html>
	
