<?php  
    include('../dbinterface.php');
  
    session_start();

    if (!isset($_SESSION['auth'])) {
    // if not redirect to login screen. 
		header('Location: ../index.php');
    } else {
		$firstName = $_SESSION['firstName'];
		$lastName = $_SESSION['lastName'];
		$email = $_SESSION['email'];
		$info = getCurPosInfo($email);
	}  
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
								<a class="navbar-brand" href="profile.php"><span class="glyphicon glyphicon-user"></span> <?= $firstName[0].". ".$lastName ?></a>
							</div> <!-- End navbar-header -->					
	    
							<div class="collapse navbar-collapse" id="navigationbar">
								<ul class="nav navbar-nav">
									<li><a href="student.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
									<li  class="active"><a href="cur_pos.php"><span class="glyphicon glyphicon-th-list"></span> Current Positions</a></li>
									<li><a href="search.php"><span class="glyphicon glyphicon-inbox"></span> Position Search</a></li>
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
									<th>ID</th>
									<th>Course Number</th>
									<th>Course Name</th>
									<th>Type</th>
									<th>Location</th>
									<th>Time</th>
									<th>Compensation</th>
								</tr>
								<?php
									foreach($info as $row) {
								?>
									<tr>
										<td><?= $row[CID]?></td>
										<td><?= $row[ClassName]?></td>
										<td><?= $row[ClassTitle]?></td>
										<td><?= $row[type]?></td>
										<td><?= $row[classRoom]?></td>
										<td><?= $row[startTime]." - ".$row[endTime]?></td>
										<td><?= $row[compensation]?></td>
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
