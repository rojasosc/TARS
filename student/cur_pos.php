<?php  
    include('studentSession.php');
	$positions = studentPositions($email);
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>TARS</title>
		
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="student.css" rel="stylesheet">
		<link href="cur_pos.css" rel="stylesheet">
		
	</head>
  
	<body>
		<!-- BEGIN Email Modal -->
		<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailmodalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Email Professor</h1>
					</div>
					<div class="modal-body">
						<form action="cur_pos.php" method="post" id="emailprof">
							<fieldset>
								<div class="row">
									<div class="col-xs-12">
										Subject: <input type="text" name="subject" class="form-control" size="64"/>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										Content:
										<textarea class="form-control" rows="8" cols="64" form="emailprof">
										</textarea>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary" form="passrecov" value="Submit">Send</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Email Modal -->
		<!-- BEGIN Withdraw Modal -->
		<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Withdraw From Position</h1>
					</div>
					<div class="modal-body">
						<form action="withdraw.php" method="post" id="withdrawForm">
							<fieldset>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											Are you sure you want to withdraw from this position? It is highly unlikely for you to get it back after you withdraw from it. You will no longer be responsible for this position but you will also relinquish all remaining compensations for filling this position.
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-10 col-xs-offset-1">
										<p>
											If you still wish to withdraw from this position, an E-mail will be sent to your employer notifying them of your release and your reasons detailed below:
										</p>
										<textarea class="form-control" rows="8" cols="64" form="withdrawReason">
										</textarea>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success" form="withdrawForm" value="Submit">Withdraw</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Email Modal -->
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
									<th>Email</th>
									<th>Withdraw</th>
								</tr>
								<?php
									foreach($positions as $row) {
										$course = $row->getPosition()->getCourse();
										$position = $row->getPosition();
								?>
									<tr>
										<td><?= $row->getID()?></td>
										<td><?= $course->getDepartment()." ".$course->getNumber()?></td>
										<td><?= $course->getTitle()?></td>
										<td><?= $position->getPositionType()?></td>
										<td><?= "TBD"?></td>
										<td><?= $position->getTime()?></td>
										<td><?= $row->getCompensation()?></td>
										<td><a class="btn btn-default" href="#emailModal" data-toggle="modal"><span class="glyphicon glyphicon-envelope"></span> Email</a></td>
										<td><a class="btn btn-default" href="#withdrawModal" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span></a></td>
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
		
		<!-- BEGIN Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<!-- END Scripts -->
	</body>
</html>
