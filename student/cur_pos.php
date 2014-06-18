<?php  
    include('studentSession.php');
	$positions = $student->getApplications(APPROVED);
	$currentApps = $student->getApplications(PENDING);
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
		<link href="cur_pos.css" rel="stylesheet">
		<!-- END CSS -->
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="cur_pos.js"></script>
		<!-- END Scripts -->
		
	</head>
  
	<body>
		<!-- BEGIN Release Modal -->
		<div class="modal fade" id="releaseModal" tabindex="-1" role="dialog" aria-labelledby="releaseModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Release From Position</h1>
					</div>
					<div class="modal-body">
						<form action="withdraw.php" method="post" id="releaseForm">
							<fieldset>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											Are you sure you want to be released from this position? It is highly unlikely for you to get it back after you're released from it. You will no longer be responsible for this position but you will also relinquish all remaining compensations for filling this position.
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											If you still wish to be released from this position, an E-mail will be sent to your employer notifying them of your release and your reasons detailed below:
										</p>
										<textarea class="form-control" rows="8" cols="64" form="releaseForm" name="releaseReasons" id="releaseReasons"></textarea>
									</div>
								</div>
								<input name="studentID" id="studentID" type="hidden" value="<?=$student->getID()?>" />
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelRelease">Cancel</button>
						<button type="submit" class="btn btn-success" form="releaseForm" id="#releaseConfirm" value="Submit">Release</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Release Modal -->
		<!-- BEGIN Withdraw Modal -->
		<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Withdraw Application</h1>
					</div>
					<div class="modal-body">
						<form action="withdraw.php" method="post" id="withdrawForm">
							<fieldset>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											Are you sure you want to withdraw your application?
										</p>
									</div>
								</div>
								<input id="studentID" type="hidden" value="<?=$student->getID()?>" />
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal" id="withdrawCancel">Cancel</button>
						<button type="submit" class="btn btn-success" form="withdrawForm" id="#withdrawConfirm" value="Submit">Withdraw</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Release Modal -->
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
									<li  class="active"><a href="cur_pos.php"><span class="glyphicon glyphicon-th-list"></span> Current Positions</a></li>
									<li><a href="search.php"><span class="glyphicon glyphicon-inbox"></span> Position Search</a></li>
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
						<h1 class="panel-title">My Current Positions</h1>
					</div>
					<div class="panel-body">	
						<!-- BEGIN Current Positions Table -->
						<table class="table table-striped">
							<tr>
								<th>Position ID</th>
								<th>Course Number</th>
								<th>Course Name</th>
								<th>Type</th>
								<th>Location</th>
								<th>Time</th>
								<th>Compensation</th>
								<th>Withdraw</th>
							</tr>
							<?php
		foreach($positions as $row) {
		$course = $row->getPosition()->getCourse();
		$position = $row->getPosition();
							?>
							<tr>
								<td class="positionID"><?= $position->getID()?></td>
								<td><?= $course->getDepartment()." ".$course->getNumber()?></td>
								<td><?= $course->getTitle()?></td>
								<td><?= $position->getPositionType()?></td>
								<td><?= "TBD"?></td>
								<td><?= $position->getTime()?></td>
								<td><?= $row->getCompensation()?></td>
								<td><a class="btn btn-default releaseButton" href="#releaseModal" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span></a></td>
							</tr>
							<?php
		}
							?>
						</table>
						<!-- END Current Positions Table -->
					</div>
				</div>
				<div class="panel panel-primary"> 
					<div class="panel-heading">
						<h1 class="panel-title">My Pending Applications</h1>
					</div>
					<div class="panel-body">	
						<!-- BEGIN Current Positions Table -->
						<table class="table table-striped">
							<tr>
								<th>Position ID</th>
								<th>Course Number</th>
								<th>Course Name</th>
								<th>Type</th>
								<th>Location</th>
								<th>Time</th>
								<th>Compensation</th>
								<th>Withdraw</th>
							</tr>
							<?php
		foreach($currentApps as $app) {
		$appCourse = $app->getPosition()->getCourse();
		$appPosition = $app->getPosition();
							?>
							<tr>
								<td class="positionID"><?= $appPosition->getID()?></td>
								<td><?= $appCourse->getDepartment()." ".$appCourse->getNumber()?></td>
								<td><?= $appCourse->getTitle()?></td>
								<td><?= $appPosition->getPositionType()?></td>
								<td><?= "TBD"?></td>
								<td><?= $appPosition->getTime()?></td>
								<td><?= $app->getCompensation()?></td>
								<td><a class="btn btn-default withdrawButton" href="#withdrawModal" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span></a></td>
							</tr>
							<?php
		}
							?>
						</table>
						<!-- END Current Positions Table -->
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
