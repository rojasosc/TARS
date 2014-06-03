<?php  
    include('studentSession.php');
	$positions = search($_POST['search'], $_POST['term'], $_POST['type']);
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
		<link href="search.css" rel="stylesheet">
		
	</head>
  
	<body>
		<!-- BEGIN Info Modal -->
		<div class="modal fade" id="infomodal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h1 class="modal-title">Position Types Details</h1>
					</div>
					<div class="modal-body">
						<h2>Workshop Leader</h2>
						<h3>Responsibilities</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Times</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Compensation</h3>
						<p>
							Stuffity stuff
						</p>
						<hr/>
						<h2>Lab TA</h2>
						<h3>Responsibilities</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Times</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Compensation</h3>
						<p>
							Stuffity stuff
						</p>
						<hr/>
						<h2>Grader</h2>
						<h3>Responsibilities</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Times</h3>
						<p>
							Stuffity stuff
						</p>
						<h3>Compensation</h3>
						<p>
							Stuffity stuff
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END Info Modal -->
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
									<li><a href="cur_pos.php"><span class="glyphicon glyphicon-th-list"></span> Current Positions</a></li>
									<li class="active"><a href="search.php"><span class="glyphicon glyphicon-inbox"></span> Position Search</a></li>
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
						<h1 class="panel-title">Position Search</h1>
					</div>
					<div class="panel-body">
						<div class="container-fluid display-area">
							<form role="form" action="search.php" method="post" id="searchForm">
								<div class="row" id="inputrow">
									<div class="col-xs-6">
										Search:
										<input type="text" name="search" class="form-control" placeholder="Search..." />
									</div>
									<div class="col-xs-3">
										Term:
										<select class="form-control" name="term">
											<option value="20142">Fall 2014</option>
											<option value="20141">Spring 2014</option>
											<option value="20132">Fall 2013</option>
											<option value="20131">Spring 2013</option>
										</select>
									</div>
									<div class="col-xs-3">
										Type:
										<select name="type" class="form-control">
											<option value="All">All</option>
											<option value="Workshop Leader">Workshop Leader</option>
											<option value="Lab TA">Lab TA</option>
											<option value="Grader">Grader</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-2 col-xs-offset-5">
										<input type="submit" value="Search" class="btn btn-primary"/>
									</div>
								</div>
							</form>				
							<hr/>
							<div id="search-results">
								<table class="table table-striped">
									<tr>
										<th>Position No.</th>
										<th>Course Number</th>
										<th>Course</th>
										<th>Professor</th>
										<th>Position Type <button class="btn btn-default" data-target="#infomodal" data-toggle="modal"><span class="glyphicon glyphicon-info-sign"></span></button></th>
										<th>Time</th>
										<th>Compensation</th>
										<th></th>
									</tr>
									<?php
										foreach($positions as $rows) {
									?>
										<tr>
											<td><?=$rows['positionID']?></td>
											<td><?=$rows['courseNumber']?></td>
											<td><?=$rows['courseTitle']?></td>
											<td><?=$rows['firstName']." ".$rows['lastName']?></td>
											<td><?=$rows['type']?></td>
											<td><?=$rows['time']?></td>
											<td>
												
												<button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Apply</button>
											</td>
										</tr>
									<?php
										}
									?>
								</table>
							</div>
						</div>
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
		<script src="search.js"></script>
		<!-- END Scripts -->
	</body>
</html>
