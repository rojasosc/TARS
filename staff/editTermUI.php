<?php
require_once 'staffSession.php';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Edit Term</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="staff.css" rel="stylesheet">
		<link href="editTermUI.css" rel="stylesheet">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
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
<?php
if ($error != null) {
	echo $error->toHTML();
}
if ($error == null || $error->getAction() != Event::SESSION_CONTINUE) {
?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h1 class="panel-title">Fall 2014</h1>
					</div>
					<div class="panel-body">
						<div class="panel panel-info coursePanel">
							<div class="panel-heading">
								<h2 class="panel-title" data-toggle="collapse" data-target="#csc171Panel">CSC 171<span class="hidden-xs">: The Science of Programming</span></h2>
							</div>
							<div class="panel-collapse collapse in" id="csc171Panel">
								<div class="panel-body" >
									<div class="container-fluid">
										<form role="form" action="#" method="post" id="csc171">
											<div class="row">
												<h3>Course Info</h3><br />
												<div class="col-xs-6 col-sm-2">
													CRN: <input type="text" class="form-control CRN" value="30105"/>
												</div>
												<div class="col-xs-6 col-sm-2">
													Course #: <input type="text" class="form-control courseNum" value="171"/> 
												</div>
												<div class="col-xs-12 col-sm-4">
													Course Title: <input type="text" class="form-control courseTitle" value="The Science of Programming"/>
												</div>
												<div class="col-xs-6 col-sm-2">
													Building: <input type="text" class="form-control building" value="HOYT"/>
												</div>
												<div class="col-xs-6 col-sm-2">
													Room: <input type="text" class="form-control room" value="AUD"/>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12 col-sm-4">
													Instructor: <input type="text" class="form-control instructor" value="Ted Pawlicki"/>
												</div>
												<div class="col-xs-4 col-sm-2">
													Day: <input type="text" class="form-control day" value="WF"/>
												</div>
												<div class="col-xs-4 col-sm-2">
													Start: <input type="text" class="form-control startTime" value="1025"/>
												</div>
												<div class="col-xs-4 col-sm-2">
													End: <input type="text" class="form-control endTime" value="1140"/>
												</div>
											</div>
											<div class="row">
												<h3>TA Counts</h3><br />
												<div class="col-xs-2">
													Lecture: <input type="text" class="form-control lecTACount" value="2"/>
												</div>
												<div class="col-xs-2">
													Lab: <input type="text" class="form-control labTACount" value="6"/>
												</div>
												<div class="col-xs-2">
													W<span class="hidden-xs hidden-sm">o</span>rksh<span class="hidden-xs hidden-sm">o</span>p: <input type="text" class="form-control wsTACount" value="15"/>
												</div>
												<div class="col-xs-2">
													Super <span class="hidden-xs hidden-sm">Leader</span>: <input type="text" class="form-control slTACount" value="1"/>
												</div>
												<div class="col-xs-2">
													Grader: <input type="text" class="form-control graderCount" value="5"/>
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</body>	
</html>
